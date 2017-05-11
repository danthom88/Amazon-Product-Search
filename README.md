# Amazon-Product-Search
Signing for V2 and V4 in PHP

The documentation for the product search on Amazon is broken. It says to use your access key to hash for signing. This is wrong. You have to generate a new access key and read the downloaded file for your private key. It does not do this with your first key.

The documentation also does not tell you that a signing version is required in the URL.

After a few hours of scratching my head and finding the Internet completely void of anything even remotely helpful, I bring you a solution in PHP.

Now, this is based off of some amazing work by Ulrich Mierendorff which had a copyright ending in 2012. It, very unfortunately, did not work with the NEWEST version of Amazons Product Search, but I figured it out by pure trial and error (luck).
