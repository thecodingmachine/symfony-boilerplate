<?php

declare(strict_types=1);

namespace App\DevTools\PHPStan;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassMethodNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;
use ReflectionAttribute;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use function array_filter;
use function array_values;

/** @implements  Rule<InClassMethodNode> */
class RouteSecurityChecker implements Rule
{
    public function getNodeType(): string
    {
        return InClassMethodNode::class;
    }

    /** @inheritdoc */
    public function processNode(Node $node, Scope $scope): array
    {
        \assert($node instanceof InClassMethodNode);
        $className                        = $scope->getClassReflection()?->getName();
        $functionName                     = $scope->getFunctionName();
        if (! $className || ! $functionName) {
            return [];
        }

        try {
            $reflection = new ReflectionMethod($className, $functionName);
        } catch (ReflectionException) {
            return [];
        }

        $attributes = $reflection->getAttributes();

        // Parse only Routes
        $routeAttribute =  $this->getAttribute(Route::class, $attributes);
        if (! $routeAttribute) {
            return [];
        }

        if ($this->functionAttributesContain(IKnowWhatImDoingThisIsAPublicRoute::class, $attributes)) {
            return [];
        }

        $isGrantedAttribute =  $this->getAttribute(IsGranted::class, $attributes);

        // LVL 1 NO PROTECTION AT ALL :: Missing vertical controls : no IsGranted and no denyAccessUnlessGranted (even without subject)
        if ($isGrantedAttribute === null) {
            if (! $this->isDenyAccessUnlessGrantedCalledInRouteFunction($node, false)) {
                return $this->buildError(
                    sprintf('🛑🔓 SECURITY: Route %s::%s is public !', $className, $functionName),
                    "Add an #[IsGranted] attribute or use the `denyAccessUnlessGranted` function.
                    If you are sure that this route should remain public, add the " . IKnowWhatImDoingThisIsAPublicRoute::class . ' attribute',
                );
            }
        }

        if ($this->functionAttributesContain(ThisRouteDoesntNeedAVoter::class, $attributes)){
            return [];
        }

        // LVL 2 VERTICAL ACCESS ONLY :: IsGranted is present BUT no voter is called
        if ($isGrantedAttribute === null) {
            if (! $this->isDenyAccessUnlessGrantedCalledInRouteFunction($node, true)) {
                return $this->buildError(
                    sprintf('🛑🔓 SECURITY: Route %s::%s is insufficiently protected !', $className, $functionName),
                    "Pass the 'subject' argument to the \$this->denyAccessUnlessGranted() call.
                    If you are sure that this route's protection should only on user's permissions, add a ".ThisRouteDoesntNeedAVoter::class." attribute.",
                );
            }
        }else{
            $isGrantedAttributeInstance = $isGrantedAttribute->newInstance();
            \assert($isGrantedAttributeInstance instanceof IsGranted);
            if ($isGrantedAttributeInstance->subject === null) {
                return $this->buildError(
                    sprintf('🛑🔓 SECURITY: Route %s::%s is insufficiently protected !', $className, $functionName),
                    "Pass the 'subject' argument to the 'IsGranted' attribute.
                    If you are sure that this route's protection should only on user's permissions, add a ".ThisRouteDoesntNeedAVoter::class.' attribute.',
                );
            }
        }

        return [];
    }

    /** @param array<ReflectionAttribute<object>> $attributes */
    private function functionAttributesContain(string $attributeClass, array $attributes): bool
    {
        return \count($this->getAttributes($attributeClass, $attributes)) > 0;
    }

    /**
     * @param array<ReflectionAttribute<object>> $attributes
     *
     * @return ReflectionAttribute<object>|null
     */
    private function getAttribute(string $attributeClass, array $attributes): ReflectionAttribute|null
    {
        $attributes = $this->getAttributes($attributeClass, $attributes);

        return \count($attributes) > 0 ? $attributes[0] : null;
    }

    /**
     * @param array<ReflectionAttribute<object>> $attributes
     *
     * @return array<ReflectionAttribute<object>>
     */
    private function getAttributes(string $attributeClass, array $attributes): array
    {
        return array_values(array_filter(
            $attributes,
            static fn (ReflectionAttribute $attr) => $attr->getName() === $attributeClass
        ));
    }

    /**
     * @return array<RuleError>
     */
    private function buildError(string $message, string $tip): array
    {
        return [
            RuleErrorBuilder::message($message . "\n")
                ->tip($tip)
                ->build(),
        ];
    }

    private function isDenyAccessUnlessGrantedCalledInRouteFunction(InClassMethodNode $node, bool $requireSubject): bool
    {
        $visitor = new class ($requireSubject) extends NodeVisitorAbstract {
            private bool $isSecurityCheckFunctionCalled = false;

            public function __construct(private readonly bool $requireSubject)
            {
            }

            public function enterNode(Node $node): int|null
            {
                // phpcs:disable SlevomatCodingStandard.ControlStructures.EarlyExit.EarlyExitNotUsed
                if (
                    $node instanceof MethodCall && $node->name instanceof Node\Identifier
                    && $node->name->toString() === 'denyAccessUnlessGranted'
                    && (!$this->requireSubject || isset($node->args[1]))
                ) {
                    $this->isSecurityCheckFunctionCalled = true;

                    return NodeTraverser::STOP_TRAVERSAL;
                }

                return null;
                // phpcs:enable SlevomatCodingStandard.ControlStructures.EarlyExit.EarlyExitNotUsed
            }

            public function isIsSecurityCheckFunctionCalled(): bool
            {
                return $this->isSecurityCheckFunctionCalled;
            }
        };

        $traverser = new NodeTraverser();
        $traverser->addVisitor($visitor);

        $traverser->traverse($node->getOriginalNode()->stmts ?? []);

        return $visitor->isIsSecurityCheckFunctionCalled();
    }
}
