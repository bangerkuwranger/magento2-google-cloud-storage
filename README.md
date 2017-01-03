Google Cloud Storage Extension for Magento 2
============================================

This extension allows retailers to upload and sync their catalogue and WYSIWYG
images to/with Google Cloud Storage. Based heavily on [Arkade S3
extension](https://github.com/shobhitsinghal624/magento2-s3).

 

Benefits
--------

 

### Sync all your media images

The following images are automatically synced with GCS:

-   Product images

-   Generated thumbnails

-   WYSIWYG images

-   Category images

 

### Magento can now scale horizontally

Complex file syncing between multiple servers is now a thing of the past. Your
servers will be able to share the one GCS bucket as the source for media files.

 

Installation
------------

The latest release will be available on composer. In the Magento root folder,
simply enter 'composer require bangerkuwranger/magento2-google-cloud-storage' in
the command line and then 'composer update' to install. You'll probably want to
update the db and recompile Magento after to be on the safe side.

 

Support
-------

Feel free to [create a GitHub
issue](https://github.com/bangerkuwranger/magento2-google-cloud-storage/issues/new)
for support regarding this extension; support will be somewhat limited, but
we'll try to address any issues if possible

 

FAQs
----

 

### Does this extension upload my log files?

No, this only syncs files in the pub/media folder. You will need to find an
alternative solution to store your log files.

 

### We did something wrong and all our images are gone! Can you restore it?

We always recommend taking a backup of your media files when switching file
storage systems. Unfortunately, we won’t be able to restore images if you
somehow accidentally delete them.

 

Credits
-------

Original logic was written for Amazon S3 by [Thai
Phan](https://github.com/thaiphan)

Initial port and some logic changes for GCS done by [Chad A.
Carino](https://github.com/bangerkuwranger)

Complete rewrite of logic, namespacing, and all GCS functions within Magento 2
context done by [Ramki Rajamanickam](https://github.com/ram10raj)
