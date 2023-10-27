(window.webpackJsonp=window.webpackJsonp||[]).push([[30],{137:function(e,t,n){"use strict";n.d(t,"a",(function(){return p})),n.d(t,"b",(function(){return u}));var a=n(0),i=n.n(a);function o(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function r(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,a)}return n}function c(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?r(Object(n),!0).forEach((function(t){o(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):r(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function l(e,t){if(null==e)return{};var n,a,i=function(e,t){if(null==e)return{};var n,a,i={},o=Object.keys(e);for(a=0;a<o.length;a++)n=o[a],t.indexOf(n)>=0||(i[n]=e[n]);return i}(e,t);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);for(a=0;a<o.length;a++)n=o[a],t.indexOf(n)>=0||Object.prototype.propertyIsEnumerable.call(e,n)&&(i[n]=e[n])}return i}var s=i.a.createContext({}),m=function(e){var t=i.a.useContext(s),n=t;return e&&(n="function"==typeof e?e(t):c(c({},t),e)),n},p=function(e){var t=m(e.components);return i.a.createElement(s.Provider,{value:t},e.children)},b={inlineCode:"code",wrapper:function(e){var t=e.children;return i.a.createElement(i.a.Fragment,{},t)}},d=i.a.forwardRef((function(e,t){var n=e.components,a=e.mdxType,o=e.originalType,r=e.parentName,s=l(e,["components","mdxType","originalType","parentName"]),p=m(n),d=a,u=p["".concat(r,".").concat(d)]||p[d]||b[d]||o;return n?i.a.createElement(u,c(c({ref:t},s),{},{components:n})):i.a.createElement(u,c({ref:t},s))}));function u(e,t){var n=arguments,a=t&&t.mdxType;if("string"==typeof e||a){var o=n.length,r=new Array(o);r[0]=d;var c={};for(var l in t)hasOwnProperty.call(t,l)&&(c[l]=t[l]);c.originalType=e,c.mdxType="string"==typeof e?e:a,r[1]=c;for(var s=2;s<o;s++)r[s]=n[s];return i.a.createElement.apply(null,r)}return i.a.createElement.apply(null,n)}d.displayName="MDXCreateElement"},99:function(e,t,n){"use strict";n.r(t),n.d(t,"frontMatter",(function(){return r})),n.d(t,"metadata",(function(){return c})),n.d(t,"toc",(function(){return l})),n.d(t,"default",(function(){return m}));var a=n(3),i=n(7),o=(n(0),n(137)),r={title:"Doctrine Migrations",slug:"/database/doctrine-migrations"},c={unversionedId:"05_Database/2_Doctrine Migrations",id:"05_Database/2_Doctrine Migrations",isDocsHomePage:!1,title:"Doctrine Migrations",description:"TDBM integrates well with Symfony, as you are able to use the",source:"@site/docs/05_Database/2_Doctrine Migrations.md",slug:"/database/doctrine-migrations",permalink:"/symfony-boilerplate/docs/database/doctrine-migrations",editUrl:"https://github.com/thecodingmachine/symfony-boilerplate/tree/master/docs/docs/05_Database/2_Doctrine Migrations.md",version:"current",sidebar:"docs",previous:{title:"ORM",permalink:"/symfony-boilerplate/docs/database/ORM"},next:{title:"Models",permalink:"/symfony-boilerplate/docs/database/models"}},l=[{value:"Create a migration",id:"create-a-migration",children:[]},{value:"Apply migrations",id:"apply-migrations",children:[]}],s={toc:l};function m(e){var t=e.components,n=Object(i.a)(e,["components"]);return Object(o.b)("wrapper",Object(a.a)({},s,n,{components:t,mdxType:"MDXLayout"}),Object(o.b)("p",null,Object(o.b)("a",{parentName:"p",href:"https://github.com/thecodingmachine/tdbm"},"TDBM")," integrates well with Symfony, as you are able to use the\n",Object(o.b)("a",{parentName:"p",href:"https://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html"},"DoctrineMigrationsBundle"),"."),Object(o.b)("p",null,Object(o.b)("a",{parentName:"p",href:"https://github.com/thecodingmachine/tdbm"},"TDBM")," provides wrappers around this library for:"),Object(o.b)("ol",null,Object(o.b)("li",{parentName:"ol"},"Building your database structure with fluid schemas (i.e., ",Object(o.b)("inlineCode",{parentName:"li"},"$x->foo()->bar()->baz()"),")."),Object(o.b)("li",{parentName:"ol"},"Defining your GraphQL types and their fields.")),Object(o.b)("div",{className:"admonition admonition-note alert alert--secondary"},Object(o.b)("div",{parentName:"div",className:"admonition-heading"},Object(o.b)("h5",{parentName:"div"},Object(o.b)("span",{parentName:"h5",className:"admonition-icon"},Object(o.b)("svg",{parentName:"span",xmlns:"http://www.w3.org/2000/svg",width:"14",height:"16",viewBox:"0 0 14 16"},Object(o.b)("path",{parentName:"svg",fillRule:"evenodd",d:"M6.3 5.69a.942.942 0 0 1-.28-.7c0-.28.09-.52.28-.7.19-.18.42-.28.7-.28.28 0 .52.09.7.28.18.19.28.42.28.7 0 .28-.09.52-.28.7a1 1 0 0 1-.7.3c-.28 0-.52-.11-.7-.3zM8 7.99c-.02-.25-.11-.48-.31-.69-.2-.19-.42-.3-.69-.31H6c-.27.02-.48.13-.69.31-.2.2-.3.44-.31.69h1v3c.02.27.11.5.31.69.2.2.42.31.69.31h1c.27 0 .48-.11.69-.31.2-.19.3-.42.31-.69H8V7.98v.01zM7 2.3c-3.14 0-5.7 2.54-5.7 5.68 0 3.14 2.56 5.7 5.7 5.7s5.7-2.55 5.7-5.7c0-3.15-2.56-5.69-5.7-5.69v.01zM7 .98c3.86 0 7 3.14 7 7s-3.14 7-7 7-7-3.12-7-7 3.14-7 7-7z"}))),"note")),Object(o.b)("div",{parentName:"div",className:"admonition-content"},Object(o.b)("p",{parentName:"div"},"\ud83d\udce3","\xa0","\xa0","All commands have to be run in the ",Object(o.b)("inlineCode",{parentName:"p"},"api")," service (",Object(o.b)("inlineCode",{parentName:"p"},"make api"),")."))),Object(o.b)("h2",{id:"create-a-migration"},"Create a migration"),Object(o.b)("pre",null,Object(o.b)("code",{parentName:"pre",className:"language-bash",metastring:'title="console"',title:'"console"'},"php bin/console doctrine:migrations:generate\n")),Object(o.b)("p",null,"This command will generate a new empty migration in the ",Object(o.b)("em",{parentName:"p"},"src/api/migrations")," folder."),Object(o.b)("p",null,"Add a meaningful description:"),Object(o.b)("pre",null,Object(o.b)("code",{parentName:"pre",className:"language-php"},"public function getDescription() : string\n{\n    return 'Create X, Y and Z tables.';\n}\n")),Object(o.b)("p",null,"And throw the following exception in the ",Object(o.b)("inlineCode",{parentName:"p"},"down")," method:"),Object(o.b)("pre",null,Object(o.b)("code",{parentName:"pre",className:"language-php"},"public function down(Schema $schema) : void\n{\n    throw new RuntimeException('Never rollback a migration!');\n}\n")),Object(o.b)("p",null,"You may now update the ",Object(o.b)("inlineCode",{parentName:"p"},"up")," method. For instance:"),Object(o.b)("pre",null,Object(o.b)("code",{parentName:"pre",className:"language-php"},"use TheCodingMachine\\FluidSchema\\TdbmFluidSchema;\n\npublic function up(Schema $schema): void\n{\n    $db = new TdbmFluidSchema($schema);\n\n    $db->table('users')\n        ->column('id')->guid()->primaryKey()->comment('@UUID')\n        ->column('first_name')->string(255)->notNull()\n        ->column('last_name')->string(255)->notNull()\n        ->column('email')->string(255)->notNull()->unique()\n        ->column('password')->string(255)->null()->default(null)\n        ->column('locale')->string(2)->notNull()\n        ->column('profile_picture')->string(255)->null()->default(null)\n        ->column('role')->string(255)->notNull();\n\n    $db->table('reset_password_tokens')\n        ->column('id')->guid()->primaryKey()->comment('@UUID')\n        ->column('user_id')->references('users')->notNull()->unique()\n        ->column('token')->string(255)->notNull()->unique()\n        ->column('valid_until')->datetimeImmutable()->notNull();\n}\n")),Object(o.b)("div",{className:"admonition admonition-note alert alert--secondary"},Object(o.b)("div",{parentName:"div",className:"admonition-heading"},Object(o.b)("h5",{parentName:"div"},Object(o.b)("span",{parentName:"h5",className:"admonition-icon"},Object(o.b)("svg",{parentName:"span",xmlns:"http://www.w3.org/2000/svg",width:"14",height:"16",viewBox:"0 0 14 16"},Object(o.b)("path",{parentName:"svg",fillRule:"evenodd",d:"M6.3 5.69a.942.942 0 0 1-.28-.7c0-.28.09-.52.28-.7.19-.18.42-.28.7-.28.28 0 .52.09.7.28.18.19.28.42.28.7 0 .28-.09.52-.28.7a1 1 0 0 1-.7.3c-.28 0-.52-.11-.7-.3zM8 7.99c-.02-.25-.11-.48-.31-.69-.2-.19-.42-.3-.69-.31H6c-.27.02-.48.13-.69.31-.2.2-.3.44-.31.69h1v3c.02.27.11.5.31.69.2.2.42.31.69.31h1c.27 0 .48-.11.69-.31.2-.19.3-.42.31-.69H8V7.98v.01zM7 2.3c-3.14 0-5.7 2.54-5.7 5.68 0 3.14 2.56 5.7 5.7 5.7s5.7-2.55 5.7-5.7c0-3.15-2.56-5.69-5.7-5.69v.01zM7 .98c3.86 0 7 3.14 7 7s-3.14 7-7 7-7-3.12-7-7 3.14-7 7-7z"}))),"note")),Object(o.b)("div",{parentName:"div",className:"admonition-content"},Object(o.b)("p",{parentName:"div"},"\ud83d\udce3","\xa0","\xa0","A table name should be plural."))),Object(o.b)("p",null,"If you're updating an existing table, it would be better to edit the corresponding migration instead of creating\na new migration."),Object(o.b)("div",{className:"admonition admonition-note alert alert--secondary"},Object(o.b)("div",{parentName:"div",className:"admonition-heading"},Object(o.b)("h5",{parentName:"div"},Object(o.b)("span",{parentName:"h5",className:"admonition-icon"},Object(o.b)("svg",{parentName:"span",xmlns:"http://www.w3.org/2000/svg",width:"14",height:"16",viewBox:"0 0 14 16"},Object(o.b)("path",{parentName:"svg",fillRule:"evenodd",d:"M6.3 5.69a.942.942 0 0 1-.28-.7c0-.28.09-.52.28-.7.19-.18.42-.28.7-.28.28 0 .52.09.7.28.18.19.28.42.28.7 0 .28-.09.52-.28.7a1 1 0 0 1-.7.3c-.28 0-.52-.11-.7-.3zM8 7.99c-.02-.25-.11-.48-.31-.69-.2-.19-.42-.3-.69-.31H6c-.27.02-.48.13-.69.31-.2.2-.3.44-.31.69h1v3c.02.27.11.5.31.69.2.2.42.31.69.31h1c.27 0 .48-.11.69-.31.2-.19.3-.42.31-.69H8V7.98v.01zM7 2.3c-3.14 0-5.7 2.54-5.7 5.68 0 3.14 2.56 5.7 5.7 5.7s5.7-2.55 5.7-5.7c0-3.15-2.56-5.69-5.7-5.69v.01zM7 .98c3.86 0 7 3.14 7 7s-3.14 7-7 7-7-3.12-7-7 3.14-7 7-7z"}))),"note")),Object(o.b)("div",{parentName:"div",className:"admonition-content"},Object(o.b)("p",{parentName:"div"},"\ud83d\udce3","\xa0","\xa0",Object(o.b)("strong",{parentName:"p"},"Do not")," edit a migration if a remote environment like your production did apply the migration."))),Object(o.b)("h2",{id:"apply-migrations"},"Apply migrations"),Object(o.b)("pre",null,Object(o.b)("code",{parentName:"pre",className:"language-bash",metastring:'title="console"',title:'"console"'},"php bin/console doctrine:migrations:migrate -n\n")),Object(o.b)("p",null,"This command will apply the new migrations to the database."),Object(o.b)("div",{className:"admonition admonition-note alert alert--secondary"},Object(o.b)("div",{parentName:"div",className:"admonition-heading"},Object(o.b)("h5",{parentName:"div"},Object(o.b)("span",{parentName:"h5",className:"admonition-icon"},Object(o.b)("svg",{parentName:"span",xmlns:"http://www.w3.org/2000/svg",width:"14",height:"16",viewBox:"0 0 14 16"},Object(o.b)("path",{parentName:"svg",fillRule:"evenodd",d:"M6.3 5.69a.942.942 0 0 1-.28-.7c0-.28.09-.52.28-.7.19-.18.42-.28.7-.28.28 0 .52.09.7.28.18.19.28.42.28.7 0 .28-.09.52-.28.7a1 1 0 0 1-.7.3c-.28 0-.52-.11-.7-.3zM8 7.99c-.02-.25-.11-.48-.31-.69-.2-.19-.42-.3-.69-.31H6c-.27.02-.48.13-.69.31-.2.2-.3.44-.31.69h1v3c.02.27.11.5.31.69.2.2.42.31.69.31h1c.27 0 .48-.11.69-.31.2-.19.3-.42.31-.69H8V7.98v.01zM7 2.3c-3.14 0-5.7 2.54-5.7 5.68 0 3.14 2.56 5.7 5.7 5.7s5.7-2.55 5.7-5.7c0-3.15-2.56-5.69-5.7-5.69v.01zM7 .98c3.86 0 7 3.14 7 7s-3.14 7-7 7-7-3.12-7-7 3.14-7 7-7z"}))),"note")),Object(o.b)("div",{parentName:"div",className:"admonition-content"},Object(o.b)("p",{parentName:"div"},"\ud83d\udce3","\xa0","\xa0","In development, the ",Object(o.b)("inlineCode",{parentName:"p"},"api")," service does it on startup."))),Object(o.b)("p",null,"If you've edited an existing migration, you'll have to reset the database first:"),Object(o.b)("pre",null,Object(o.b)("code",{parentName:"pre",className:"language-bash",metastring:'title="console"',title:'"console"'},"php bin/console doctrine:database:drop -n --force &&\nphp bin/console doctrine:database:create -n &&\nphp bin/console doctrine:migrations:migrate -n\n")),Object(o.b)("div",{className:"admonition admonition-note alert alert--secondary"},Object(o.b)("div",{parentName:"div",className:"admonition-heading"},Object(o.b)("h5",{parentName:"div"},Object(o.b)("span",{parentName:"h5",className:"admonition-icon"},Object(o.b)("svg",{parentName:"span",xmlns:"http://www.w3.org/2000/svg",width:"14",height:"16",viewBox:"0 0 14 16"},Object(o.b)("path",{parentName:"svg",fillRule:"evenodd",d:"M6.3 5.69a.942.942 0 0 1-.28-.7c0-.28.09-.52.28-.7.19-.18.42-.28.7-.28.28 0 .52.09.7.28.18.19.28.42.28.7 0 .28-.09.52-.28.7a1 1 0 0 1-.7.3c-.28 0-.52-.11-.7-.3zM8 7.99c-.02-.25-.11-.48-.31-.69-.2-.19-.42-.3-.69-.31H6c-.27.02-.48.13-.69.31-.2.2-.3.44-.31.69h1v3c.02.27.11.5.31.69.2.2.42.31.69.31h1c.27 0 .48-.11.69-.31.2-.19.3-.42.31-.69H8V7.98v.01zM7 2.3c-3.14 0-5.7 2.54-5.7 5.68 0 3.14 2.56 5.7 5.7 5.7s5.7-2.55 5.7-5.7c0-3.15-2.56-5.69-5.7-5.69v.01zM7 .98c3.86 0 7 3.14 7 7s-3.14 7-7 7-7-3.12-7-7 3.14-7 7-7z"}))),"note")),Object(o.b)("div",{parentName:"div",className:"admonition-content"},Object(o.b)("p",{parentName:"div"},"\ud83d\udce3","\xa0","\xa0","Reminder: ",Object(o.b)("strong",{parentName:"p"},"Do not")," edit a migration if a remote environment like your production did apply the migration. "))))}m.isMDXComponent=!0}}]);