# q-zip-multiples
A very basic example compression program created for a Quora answer.  
[(Rich Kosiba's Answer to Is it possible to store/compression 1GB of information in 1MB, and why not? What are the limitations?)](https://qr.ae/TmFqrB)

This is very simplistic and will actually enlarge most files.

I chose PHP because its syntax is easy for most people with programming experience to understand __and__ it's available by default on many operating systems and easy to install on those which do not have it.

Some might dislike the heavy commenting. This is not intended to be serious code but rather instructional. I expect that people who don't know PHP will be reading it.


```
Usage:
  php q-zip.php <command> <input> [output]

Arguments:
  command   Can be "compress", "decompress", or "calculate".
  input     The path to the input file.
  output    The path to the output file.
```


___Note:__ `PHP_EOL` is a constant that holds the "End Of Line" string for whatever platform you're running your script on._
