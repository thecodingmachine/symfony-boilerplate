---
title: Temporary Files
slug: /files/temporary-files
---

Your API might have to manage temporary files, i.e., files you manipulate and forget.

An option would be to save them in the *src/api/public* folder (don't forget to update the *src/api/public/.htaccess* 
file for security reason) and delete them once your API has served them to the user.

For instance:

```php title="src/api/src/Infrastructure/Controller/DownloadXLSXController.php"
use function Safe\file_get_contents;
use function Safe\unlink;

protected function createResponseWithXLSXAttachment(string $filename, Xlsx $xlsx): Response
{
    try {
        $tmpFilename = Uuid::uuid4()->toString() . '.xlsx';
        $xlsx->save($tmpFilename);
        $fileContent = file_get_contents($tmpFilename); // Get the file content.
    } finally {
        if (file_exists($tmpFilename)) {
            unlink($tmpFilename); // Delete the file.
        }
    }
    
    return $this->createResponseWithAttachment(
        $filename,
        $fileContent
    );
}
```
