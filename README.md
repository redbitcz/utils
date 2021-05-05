# Redbit Utils

Lightweight utilities for logging, IO, and Unix-like process signal

## Installation

Install via Composer:

```shell
composer install redbitcz/utils
```

## Requirements 
Package requires PHP version 7.3 and above.

For handling Unix-like process signals requires the `pcntl` and `posix` PHP extensions. Without that support
related method call will be siletly ignored.

## Usage

### `Logger`

The `\Redbitcz\Utils\Log\Logger` class is implementation of PSR-3 logger interface and it decorates each
logger record with time and log severity name.

Example:
```
[2021-05-05 11:49:36] Logged message 1
[2021-05-05 11:49:38] Another logged message
```

Logger requires Writer `\Redbitcz\Utils\IO\IOutStream` instance. Package contains few several types
of Writer implementations which are different by the log target (console, general output, standard output, HTML output,
or file).

See [`Logger` example](examples/log/output-logger.php).

### `Progress`
The `\Redbitcz\Utils\Log\Progress` class is simple generator of progress status to reporting progress of operations.
In additive is added the time spent is each step and whole operation.

Example: 
```shell
[2021-05-05 10:40:06] DEBUG: [ 0.000s/ 0.000] step 1/9: Logged step 1
[2021-05-05 10:40:06] DEBUG: [ 0.000s/ 0.000] step 2/9: Another logged message
[2021-05-05 10:40:06] DEBUG: [ 0.371s/ 0.371] step 3/9: Foo
[2021-05-05 10:40:10] DEBUG: [ 3.900s/ 4.271] step 4/9: Bar
[2021-05-05 10:40:10] DEBUG: [ 0.000s/ 4.271] step 5/9: Foo Bar
[2021-05-05 10:40:10] DEBUG: [ 0.000s/ 4.271] step 6/9: Foo Baz
[2021-05-05 10:40:10] DEBUG: [ 0.000s/ 4.271] step 7/9: Foo comes to the Bar
[2021-05-05 10:40:11] DEBUG: [ 0.212s/ 4.483] step 8/9: Hey Donald, get off that chandelier! 
[2021-05-05 10:40:11] DEBUG: [ 0.001s/ 4.484] step 9/9: All Done
```
See [`Progress` example](examples/log/progress.php).

## `ProcessTerminationLock`

The `\Redbitcz\Utils\Process\ProcessTerminationLock` class is simple mechanism how to prevent (rspt. delay) unexpected
exit of PHP process during operation processing. It's recommended to workers to prevent break during processing a job
and similar usage in processes managed by a Process Control system (`systemd`, `supervisor`, etc.).

Example:

```php
while(true) {
    $job = $worker->waitToJob();
    
    ProcessTerminationLock::lock(); // Lock termination to prevent break job processing
    
    //... long job processing  

    ProcessTerminationLock::unlock(); // Unlock
}
```
See [`ProcessTerminationLock` example](examples/process/termination-lock.php).

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Contact
Redbit s.r.o. - @redbitcz - info@redbit.cz


