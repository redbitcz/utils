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

### `Locker`

The `\Redbitcz\Utils\Lock\Locker` class is simple implementation of lock/semaphor based of filelock. It's optimized for
Linux architecture. 

Locker support two modes:

 - **Blocking mode** – Blocking mode is create semaphor for locked space, all concurrent locks will **wait to release
    previous lock**. Be careful, it may cause to deadlock of PHP processes, because lock at filesystem is not subject of
    [`max_execution_time`](https://www.php.net/manual/en/info.configuration.php#ini.max-execution-time) limit!
 - **Non blocking mode** – Non blocking mode is create lock which is prevent access concurrent processes to locked stage. 
    All concurent locks **will imediatelly fails** with `LockObtainException` Exception.

Example non-blocking lock: 

```php
    $locker = new Locker(__DIR__, 'example', Locker::NON_BLOCKING);
    
    try {
        $locker->lock();
        
        // ... exclusive operation
        
        $locker->unlock();
    }
    catch (LockObtainException $e) {
        die('Error: Another process is alreasy processing that stuff');
    }
```

See [Non-blocking `Locker` example](examples/lock/non-blocking-locker.php).

Example blocking lock:

```php
    $locker = new Locker(__DIR__, 'example', Locker::BLOCKING);
    
    $locker->lock(); // concurrent process will be wait here to release previous lock
    
    // ... exclusive operation
    
    $locker->unlock();
```

See [Blocking `Locker` example](examples/lock/blocking-locker.php).

### `Logger`

The `\Redbitcz\Utils\Log\Logger` class is implementation of PSR-3 logger interface and it decorates each
logger record with time and log severity name.

Example:
```
[2021-05-05 11:49:36] INFO: Logged message 1
[2021-05-05 11:49:38] DEBUG: Another logged message
```

Logger requires Writer `\Redbitcz\Utils\IO\IOutStream` instance. Package contains few several types
of Writer implementations which are different by the log target (console, general output, standard output, HTML output,
or file).

Logger also support sectionalization for long-processing operations:

Example:

```php
$logger->info("Processing message: $messageId");

$messageLogger = $logger->section($messageId);
$messageLogger->info('Open');

function parse(LoggerInterface $parserLogger) {
    $parserLogger->info('Parsing...');
    // ...
    $parserLogger->info('Parsing OK');
}

parse($messageLogger->section('parser'));

$messageLogger->info('Save');

$logger->info('Done');
```

Sends to output:
```
[2021-05-05 11:49:36] INFO: Processing message: 123456789
[2021-05-05 11:49:37] INFO: {123456789} Open
[2021-05-05 11:49:38] INFO: {123456789/parser} Parsing...
[2021-05-05 11:49:38] INFO: {123456789/parser} Parsing OK
[2021-05-05 11:49:38] INFO: {123456789} Save
[2021-05-05 11:49:36] INFO: Done
```

Section is useful to provide logger to another service which is requested to process single entity.

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

### `ProcessTerminationLock`

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

### `BitwiseVariator`
Classes in `\Redbitcz\Utils\Bitwise` namespace provides filtered bit variations generator over
[Bitwise values](https://en.wikipedia.org/wiki/Bitwise_operation).

That mean, when you have bits `1011`, variator generates all bits variations.

```php
$variations = BitwiseVariator::create(0b1011)->variate();
```

| Variation for bits `1011` |
|--------------------------:|
|                    `0000` |
|                    `0001` |
|                    `0010` |
|                    `0011` |
|                    `1000` |
|                    `1001` |
|                    `1010` |
|                    `1011` |

#### Filters

`BitwiseVariator` class provide filter to select variations with(out) some bits only.

```php
$variations = BitwiseVariator::create(0b1011)->must(0b0010)->variate();
```

| Variation for bits `1011` with bite `0010` |
|-------------------------------------------:|
|                                     `0010` |
|                                     `0011` |
|                                     `1010` |
|                                     `1011` |


```php
$variations = BitwiseVariator::create(0b1011)->mustNot(0b0010)->variate();
```

| Variation for bits `1011` without bite `0010` |
|----------------------------------------------:|
|                                        `0000` |
|                                        `0001` |
|                                        `1000` |
|                                        `1001` |

Be aware to use more than 8 variated bits, because it proceed huge of variants:

![Table with count of variants for every variated bits](https://user-images.githubusercontent.com/1657322/153865836-174cbe67-3216-4e47-954b-bec50e8d2c26.png)

(source: [Spreadseed Bitwise Variator counts](https://drive.google.com/open?id=1J4M0PyoQFTDgKk84fVjzhtil_Af0_gVZX0BdPlD5uFg))

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Contact
Redbit s.r.o. - @redbitcz - info@redbit.cz


