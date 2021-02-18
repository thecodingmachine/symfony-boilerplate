---
title: Uploads
slug: /files/uploads
---

![Uploaded files overview](/img/uploaded_file_big_picture.png)

## Storables

Like the models for the database's data, the Symfony Boilerplate provides the `Storable` abstract class. 
It's a wrapper around an upload.

You have to extend this class with a custom class, usually because of validation.

:::note

📣&nbsp;&nbsp;See the [Validation](/docs/validation/models) chapter for more details about validation.

:::

The `Storable` abstract class contains useful methods for creating one or more instances according to a source:

```php
use App\Domain\Model\Storable\MyStorable;
use Psr\Http\Message\UploadedFileInterface;

$storable = MyStorable::createFromUploadedFile(
    /** @var UploadedFileInterface $upload */
   $upload
);

$storables = MyStorable::createAllFromUploadedFiles(
    /** @var UploadedFileInterface[] $uploads */
   $uploads
);

$storable = MyStorable::createFromPath(
    /** @var string $filePath */
   $filePath
);

$storables = MyStorable::createAllFromPaths(
    /** @var string[] $filePaths */
   $filePaths
);
```

## Storages

Like the DAOs for the models, the Symfony Boilerplate provides the `PublicStorage` and `PrivateStorage` 
abstract classes for the storables:

1. `PublicStorage` for public files (i.e., files with free access).
2. `PrivateStorage` for private files (i.e., files with access control).

:::note

📣&nbsp;&nbsp;Both `PublicStorage` and `PrivateStorage` abstract classes extend the `Storage` abstract class.

:::

You have to extend one of these classes with a custom class.

It must implement the `getDirectoryName` method from the `Storage` class. 
In the storage service's bucket, it is the directory's name, which contains the files.

For instance:

```php
namespace App\Domain\Storage;

final class MyStorage extends PrivateStorage
{
    protected function getDirectoryName(): string
    {
        return 'my_storage';
    }
}
```

### Write

```php
// Create the TDBM model instance.
$foo = new Foo();

// Create the storable from the UploadedFileInterface.
$storable = MyStorable::createFromUploadedFile(
    /** @var UploadedFileInterface $upload */
   $upload
);

// Write (and validate) the upload to the storage.
$filename = $this->myStorage->write($storable);

// Save only the filename.
$foo->setFilename($filename);

// It works the same for multiple uploads.
$storables = MyStorable::createAllFromUploadedFiles(
    /** @var UploadedFileInterface[] $uploads */
   $uploads
);
$filenames = $this->myStorage->writeAll($storables);
$foo->setFilenames($filenames);
```

### Delete

```php
$filename = $foo->getFilename();
$this->myStorage->delete($filename);

$filenames = $foo->getFilenames();
$this->myStorage->deleteAll($filenames);
```

:::note

📣&nbsp;&nbsp;If you have a lot of files to delete, it might be better to do that action asynchronously.

:::

### File Exists

```php
if ($this->myStorage->fileExists('foo.txt')) {
    // ...
}
```

### Get File Content

```php
$filename = $foo->getFilename();
$fileContent = $this->myStorage->getFileContent($filename);
```

:::note

📣&nbsp;&nbsp;Method `getFileContent` should **only** be used when accessing a private file.

:::

## Public File Access

Public files have a public URL.

In the `publicRuntimeConfig` property of your *src/webapp/nuxt.config.js*, you can
add base URLs of your public bucket's directories. 

For instance:

```js title="src/webapp/nuxt.config.js"
publicRuntimeConfig: {
  myStorageURL:
    process.env.PUBLIC_STORAGE_URL +
    process.env.PUBLIC_MY_STORAGE_DIRECTORY_NAME +
    '/',
}
```

You may then concatenate the filename to this URL:

```js title="Vue component <script> block"
this.$config.myStorageURL + this.foo.filename
```

```html title="Vue component <template> block"
<img :src="$config.myStorageURL + foo.filename" />
```

:::note

📣&nbsp;&nbsp;The public URL format might differ according to your storage source.

:::

## Private File Access

You must create a controller with a Symfony route. 

This controller may extend the `DownloadController` abstract class that provides the following methods:

```php
// For files you want the browser to download.
protected function createResponseWithAttachment(string $filename, string $fileContent): Response;

// For images, etc.
protected function createResponseInline(string $filename, string $fileContent): Response;
```

For instance:

```php
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/foo/{filename}", methods={"GET"})
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
public function downloadFooFile(string $filename): Response
{
    if (! $this->myStorage->fileExists($filename)) {
        throw $this->createNotFoundException();
    }

    $fileContent = $this->myStorage->getFileContent($filename);

    return $this->createResponseInline(
        $filename,
        $fileContent
    );
}
```

On the web application, use the `$config.apiURL` and concatenate the Symfony route of your controller.

## Storage Source

We use [Flysystem](https://github.com/thephpleague/flysystem) to abstract the storage service in our source code.

We configured this package in the *src/api/config/packages/flysystem.yaml* configuration file.

In our implementation with [MinIO](https://min.io/), we use two buckets (sort of disks with access policies).

We create the two buckets thanks to the `InitializeS3StorageCommand` class:

```bash title="console"
php bin/console app:init-storage:s3
```

:::note

📣&nbsp;&nbsp;In your development environment, the service `api` runs this command automatically on startup.

:::

Let's go back to the configuration:

```yaml title="src/api/config/packages/flysystem.yaml"
public.storage.s3:
    adapter: 'aws'
    options:
        client: 's3.client'
        bucket: '%env(STORAGE_PUBLIC_BUCKET)%'

public.storage.memory:
    adapter: 'memory'

public.storage:
    adapter: 'lazy'
    options:
        source: '%env(STORAGE_PUBLIC_SOURCE)%'
```

The service `public.storage` is the generic Symfony service used in our source code. 
Thanks to its `source` option, we tell the Flysystem package to use either the `public.storage.s3` service 
(development, maybe other environments) or `public.storage.memory` service (tests).

It works the same for private storage.

## Forms

```html title="Vue component <template> block"
<b-form @submit.stop.prevent="onSubmit">
  <b-form-group
    id="input-group-file"
    :label="$t('common.file')"
    label-for="input-file"
  >
    <b-form-file
      id="input-file"
      v-model="form.file"
      :placeholder="$t('common.single_file.placeholder')"
      :drop-placeholder="$t('common.single_file.drop_placeholder')"
      :browse-text="$t('common.browse')"
      :state="formState('foo_file')"
    ></b-form-file>
    <div v-if="form.file !== null" class="mt-3">
      <FilesList :files="[form.file]" />
    </div>
    <b-form-invalid-feedback :state="formState('foo_file')">
      <ErrorsList :errors="formErrors('foo_file')" />
    </b-form-invalid-feedback>
  </b-form-group>
  <b-button
    type="submit"
    variant="primary"
  >
    {{ $t('common.submit') }}
  </b-button>
</b-form>
```

```js title="Vue component <script> block"
import { Form } from '@/mixins/form'
import ErrorsList from '@/components/forms/ErrorsList'
import FilesList from '@/components/forms/FilesList'
import { GlobalOverlay } from '@/mixins/global-overlay'
import { UploadFooFile } from '@/graphql/examples/upload_foo_file.mutation'

export default {
  components: { FilesList, ErrorsList },
  mixins: [Form, GlobalOverlay],
  data() {
    return {
      form: {
        file: null,
      },
    }
  },
  methods: {
    async onSubmit() {
      this.resetFormErrors()
      this.displayGlobalOverlay()

      try {
        const result = await this.$graphql.request(
          UploadFooFile,
          {
            file: this.form.file,
          }
        )

        // Custom logic on success.
      } catch (e) {
        this.hydrateFormErrors(e)
      } finally {
        this.hideGlobalOverlay()
      }
    },
  },
}
```

```php title="Use case"
/**
 * @throws InvalidStorable
 *
 * @Mutation
 */
public function uploadFooFile(
    UploadedFileInterface $file
): string {
    $storable = FooFile::createFromUploadedFile($file);
    $filename = $this->fooFileStorage->write($storable);
        
    return $filename;
}
```