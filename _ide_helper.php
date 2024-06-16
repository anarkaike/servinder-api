<?php
/* @noinspection ALL */
// @formatter:off
// phpcs:ignoreFile

/**
 * A helper file for Laravel, to provide autocomplete information to your IDE
 * Generated for Laravel 11.10.0.
 *
 * This file should not be included in your code, only analyzed by your IDE!
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 * @see https://github.com/barryvdh/laravel-ide-helper
 */

namespace Maatwebsite\Excel\Facades {
            /**
     * 
     *
     */        class Excel {
                    /**
         * 
         *
         * @param object $export
         * @param string|null $fileName
         * @param string $writerType
         * @param array $headers
         * @return \Symfony\Component\HttpFoundation\BinaryFileResponse 
         * @throws \PhpOffice\PhpSpreadsheet\Exception
         * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
         * @static 
         */        public static function download($export, $fileName, $writerType = null, $headers = [])
        {
                        /** @var \Maatwebsite\Excel\Excel $instance */
                        return $instance->download($export, $fileName, $writerType, $headers);
        }
                    /**
         * 
         *
         * @param string|null $disk Fallback for usage with named properties
         * @param object $export
         * @param string $filePath
         * @param string|null $diskName
         * @param string $writerType
         * @param mixed $diskOptions
         * @return bool 
         * @throws \PhpOffice\PhpSpreadsheet\Exception
         * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
         * @static 
         */        public static function store($export, $filePath, $diskName = null, $writerType = null, $diskOptions = [], $disk = null)
        {
                        /** @var \Maatwebsite\Excel\Excel $instance */
                        return $instance->store($export, $filePath, $diskName, $writerType, $diskOptions, $disk);
        }
                    /**
         * 
         *
         * @param object $export
         * @param string $filePath
         * @param string|null $disk
         * @param string $writerType
         * @param mixed $diskOptions
         * @return \Illuminate\Foundation\Bus\PendingDispatch 
         * @static 
         */        public static function queue($export, $filePath, $disk = null, $writerType = null, $diskOptions = [])
        {
                        /** @var \Maatwebsite\Excel\Excel $instance */
                        return $instance->queue($export, $filePath, $disk, $writerType, $diskOptions);
        }
                    /**
         * 
         *
         * @param object $export
         * @param string $writerType
         * @return string 
         * @static 
         */        public static function raw($export, $writerType)
        {
                        /** @var \Maatwebsite\Excel\Excel $instance */
                        return $instance->raw($export, $writerType);
        }
                    /**
         * 
         *
         * @param object $import
         * @param string|\Symfony\Component\HttpFoundation\File\UploadedFile $filePath
         * @param string|null $disk
         * @param string|null $readerType
         * @return \Maatwebsite\Excel\Reader|\Illuminate\Foundation\Bus\PendingDispatch 
         * @static 
         */        public static function import($import, $filePath, $disk = null, $readerType = null)
        {
                        /** @var \Maatwebsite\Excel\Excel $instance */
                        return $instance->import($import, $filePath, $disk, $readerType);
        }
                    /**
         * 
         *
         * @param object $import
         * @param string|\Symfony\Component\HttpFoundation\File\UploadedFile $filePath
         * @param string|null $disk
         * @param string|null $readerType
         * @return array 
         * @static 
         */        public static function toArray($import, $filePath, $disk = null, $readerType = null)
        {
                        /** @var \Maatwebsite\Excel\Excel $instance */
                        return $instance->toArray($import, $filePath, $disk, $readerType);
        }
                    /**
         * 
         *
         * @param object $import
         * @param string|\Symfony\Component\HttpFoundation\File\UploadedFile $filePath
         * @param string|null $disk
         * @param string|null $readerType
         * @return \Illuminate\Support\Collection 
         * @static 
         */        public static function toCollection($import, $filePath, $disk = null, $readerType = null)
        {
                        /** @var \Maatwebsite\Excel\Excel $instance */
                        return $instance->toCollection($import, $filePath, $disk, $readerType);
        }
                    /**
         * 
         *
         * @param \Illuminate\Contracts\Queue\ShouldQueue $import
         * @param string|\Symfony\Component\HttpFoundation\File\UploadedFile $filePath
         * @param string|null $disk
         * @param string $readerType
         * @return \Illuminate\Foundation\Bus\PendingDispatch 
         * @static 
         */        public static function queueImport($import, $filePath, $disk = null, $readerType = null)
        {
                        /** @var \Maatwebsite\Excel\Excel $instance */
                        return $instance->queueImport($import, $filePath, $disk, $readerType);
        }
                    /**
         * 
         *
         * @param string $concern
         * @param callable $handler
         * @param string $event
         * @static 
         */        public static function extend($concern, $handler, $event = 'Maatwebsite\\Excel\\Events\\BeforeWriting')
        {
                        return \Maatwebsite\Excel\Excel::extend($concern, $handler, $event);
        }
                    /**
         * When asserting downloaded, stored, queued or imported, use regular expression
         * to look for a matching file path.
         *
         * @return void 
         * @static 
         */        public static function matchByRegex()
        {
                        /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
                        $instance->matchByRegex();
        }
                    /**
         * When asserting downloaded, stored, queued or imported, use regular string
         * comparison for matching file path.
         *
         * @return void 
         * @static 
         */        public static function doNotMatchByRegex()
        {
                        /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
                        $instance->doNotMatchByRegex();
        }
                    /**
         * 
         *
         * @param string $fileName
         * @param callable|null $callback
         * @static 
         */        public static function assertDownloaded($fileName, $callback = null)
        {
                        /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
                        return $instance->assertDownloaded($fileName, $callback);
        }
                    /**
         * 
         *
         * @param string $filePath
         * @param string|callable|null $disk
         * @param callable|null $callback
         * @static 
         */        public static function assertStored($filePath, $disk = null, $callback = null)
        {
                        /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
                        return $instance->assertStored($filePath, $disk, $callback);
        }
                    /**
         * 
         *
         * @param string $filePath
         * @param string|callable|null $disk
         * @param callable|null $callback
         * @static 
         */        public static function assertQueued($filePath, $disk = null, $callback = null)
        {
                        /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
                        return $instance->assertQueued($filePath, $disk, $callback);
        }
                    /**
         * 
         *
         * @static 
         */        public static function assertQueuedWithChain($chain)
        {
                        /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
                        return $instance->assertQueuedWithChain($chain);
        }
                    /**
         * 
         *
         * @param string $classname
         * @param callable|null $callback
         * @static 
         */        public static function assertExportedInRaw($classname, $callback = null)
        {
                        /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
                        return $instance->assertExportedInRaw($classname, $callback);
        }
                    /**
         * 
         *
         * @param string $filePath
         * @param string|callable|null $disk
         * @param callable|null $callback
         * @static 
         */        public static function assertImported($filePath, $disk = null, $callback = null)
        {
                        /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
                        return $instance->assertImported($filePath, $disk, $callback);
        }
            }
    }

namespace Barryvdh\Debugbar\Facades {
            /**
     * 
     *
     * @method static void alert(mixed $message)
     * @method static void critical(mixed $message)
     * @method static void debug(mixed $message)
     * @method static void emergency(mixed $message)
     * @method static void error(mixed $message)
     * @method static void info(mixed $message)
     * @method static void log(mixed $message)
     * @method static void notice(mixed $message)
     * @method static void warning(mixed $message)
     * @see \Barryvdh\Debugbar\LaravelDebugbar
     */        class Debugbar {
                    /**
         * Enable the Debugbar and boot, if not already booted.
         *
         * @static 
         */        public static function enable()
        {
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->enable();
        }
                    /**
         * Boot the debugbar (add collectors, renderer and listener)
         *
         * @static 
         */        public static function boot()
        {
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->boot();
        }
                    /**
         * 
         *
         * @static 
         */        public static function shouldCollect($name, $default = false)
        {
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->shouldCollect($name, $default);
        }
                    /**
         * Adds a data collector
         *
         * @param \DebugBar\DataCollector\DataCollectorInterface $collector
         * @throws DebugBarException
         * @return \Barryvdh\Debugbar\LaravelDebugbar 
         * @static 
         */        public static function addCollector($collector)
        {
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->addCollector($collector);
        }
                    /**
         * Handle silenced errors
         *
         * @param $level
         * @param $message
         * @param string $file
         * @param int $line
         * @param array $context
         * @throws \ErrorException
         * @static 
         */        public static function handleError($level, $message, $file = '', $line = 0, $context = [])
        {
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->handleError($level, $message, $file, $line, $context);
        }
                    /**
         * Starts a measure
         *
         * @param string $name Internal name, used to stop the measure
         * @param string $label Public name
         * @param string|null $collector
         * @static 
         */        public static function startMeasure($name, $label = null, $collector = null)
        {
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->startMeasure($name, $label, $collector);
        }
                    /**
         * Stops a measure
         *
         * @param string $name
         * @static 
         */        public static function stopMeasure($name)
        {
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->stopMeasure($name);
        }
                    /**
         * Adds an exception to be profiled in the debug bar
         *
         * @param \Exception $e
         * @deprecated in favor of addThrowable
         * @static 
         */        public static function addException($e)
        {
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->addException($e);
        }
                    /**
         * Adds an exception to be profiled in the debug bar
         *
         * @param \Throwable $e
         * @static 
         */        public static function addThrowable($e)
        {
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->addThrowable($e);
        }
                    /**
         * Returns a JavascriptRenderer for this instance
         *
         * @param string $baseUrl
         * @param string $basePath
         * @return \Barryvdh\Debugbar\JavascriptRenderer 
         * @static 
         */        public static function getJavascriptRenderer($baseUrl = null, $basePath = null)
        {
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->getJavascriptRenderer($baseUrl, $basePath);
        }
                    /**
         * Modify the response and inject the debugbar (or data in headers)
         *
         * @param \Symfony\Component\HttpFoundation\Request $request
         * @param \Symfony\Component\HttpFoundation\Response $response
         * @return \Symfony\Component\HttpFoundation\Response 
         * @static 
         */        public static function modifyResponse($request, $response)
        {
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->modifyResponse($request, $response);
        }
                    /**
         * Check if the Debugbar is enabled
         *
         * @return boolean 
         * @static 
         */        public static function isEnabled()
        {
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->isEnabled();
        }
                    /**
         * Collects the data from the collectors
         *
         * @return array 
         * @static 
         */        public static function collect()
        {
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->collect();
        }
                    /**
         * Injects the web debug toolbar into the given Response.
         *
         * @param \Symfony\Component\HttpFoundation\Response $response A Response instance
         * Based on https://github.com/symfony/WebProfilerBundle/blob/master/EventListener/WebDebugToolbarListener.php
         * @static 
         */        public static function injectDebugbar($response)
        {
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->injectDebugbar($response);
        }
                    /**
         * Disable the Debugbar
         *
         * @static 
         */        public static function disable()
        {
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->disable();
        }
                    /**
         * Adds a measure
         *
         * @param string $label
         * @param float $start
         * @param float $end
         * @param array|null $params
         * @param string|null $collector
         * @static 
         */        public static function addMeasure($label, $start, $end, $params = [], $collector = null)
        {
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->addMeasure($label, $start, $end, $params, $collector);
        }
                    /**
         * Utility function to measure the execution of a Closure
         *
         * @param string $label
         * @param \Closure $closure
         * @param string|null $collector
         * @return mixed 
         * @static 
         */        public static function measure($label, $closure, $collector = null)
        {
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->measure($label, $closure, $collector);
        }
                    /**
         * Collect data in a CLI request
         *
         * @return array 
         * @static 
         */        public static function collectConsole()
        {
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->collectConsole();
        }
                    /**
         * Adds a message to the MessagesCollector
         * 
         * A message can be anything from an object to a string
         *
         * @param mixed $message
         * @param string $label
         * @static 
         */        public static function addMessage($message, $label = 'info')
        {
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->addMessage($message, $label);
        }
                    /**
         * Checks if a data collector has been added
         *
         * @param string $name
         * @return boolean 
         * @static 
         */        public static function hasCollector($name)
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->hasCollector($name);
        }
                    /**
         * Returns a data collector
         *
         * @param string $name
         * @return \DebugBar\DataCollector\DataCollectorInterface 
         * @throws DebugBarException
         * @static 
         */        public static function getCollector($name)
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->getCollector($name);
        }
                    /**
         * Returns an array of all data collectors
         *
         * @return \DebugBar\array[DataCollectorInterface] 
         * @static 
         */        public static function getCollectors()
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->getCollectors();
        }
                    /**
         * Sets the request id generator
         *
         * @param \DebugBar\RequestIdGeneratorInterface $generator
         * @return \Barryvdh\Debugbar\LaravelDebugbar 
         * @static 
         */        public static function setRequestIdGenerator($generator)
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->setRequestIdGenerator($generator);
        }
                    /**
         * 
         *
         * @return \DebugBar\RequestIdGeneratorInterface 
         * @static 
         */        public static function getRequestIdGenerator()
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->getRequestIdGenerator();
        }
                    /**
         * Returns the id of the current request
         *
         * @return string 
         * @static 
         */        public static function getCurrentRequestId()
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->getCurrentRequestId();
        }
                    /**
         * Sets the storage backend to use to store the collected data
         *
         * @param \DebugBar\StorageInterface $storage
         * @return \Barryvdh\Debugbar\LaravelDebugbar 
         * @static 
         */        public static function setStorage($storage = null)
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->setStorage($storage);
        }
                    /**
         * 
         *
         * @return \DebugBar\StorageInterface 
         * @static 
         */        public static function getStorage()
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->getStorage();
        }
                    /**
         * Checks if the data will be persisted
         *
         * @return boolean 
         * @static 
         */        public static function isDataPersisted()
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->isDataPersisted();
        }
                    /**
         * Sets the HTTP driver
         *
         * @param \DebugBar\HttpDriverInterface $driver
         * @return \Barryvdh\Debugbar\LaravelDebugbar 
         * @static 
         */        public static function setHttpDriver($driver)
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->setHttpDriver($driver);
        }
                    /**
         * Returns the HTTP driver
         * 
         * If no http driver where defined, a PhpHttpDriver is automatically created
         *
         * @return \DebugBar\HttpDriverInterface 
         * @static 
         */        public static function getHttpDriver()
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->getHttpDriver();
        }
                    /**
         * Returns collected data
         * 
         * Will collect the data if none have been collected yet
         *
         * @return array 
         * @static 
         */        public static function getData()
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->getData();
        }
                    /**
         * Returns an array of HTTP headers containing the data
         *
         * @param string $headerName
         * @param integer $maxHeaderLength
         * @return array 
         * @static 
         */        public static function getDataAsHeaders($headerName = 'phpdebugbar', $maxHeaderLength = 4096, $maxTotalHeaderLength = 250000)
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->getDataAsHeaders($headerName, $maxHeaderLength, $maxTotalHeaderLength);
        }
                    /**
         * Sends the data through the HTTP headers
         *
         * @param bool $useOpenHandler
         * @param string $headerName
         * @param integer $maxHeaderLength
         * @return \Barryvdh\Debugbar\LaravelDebugbar 
         * @static 
         */        public static function sendDataInHeaders($useOpenHandler = null, $headerName = 'phpdebugbar', $maxHeaderLength = 4096)
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->sendDataInHeaders($useOpenHandler, $headerName, $maxHeaderLength);
        }
                    /**
         * Stacks the data in the session for later rendering
         *
         * @static 
         */        public static function stackData()
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->stackData();
        }
                    /**
         * Checks if there is stacked data in the session
         *
         * @return boolean 
         * @static 
         */        public static function hasStackedData()
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->hasStackedData();
        }
                    /**
         * Returns the data stacked in the session
         *
         * @param boolean $delete Whether to delete the data in the session
         * @return array 
         * @static 
         */        public static function getStackedData($delete = true)
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->getStackedData($delete);
        }
                    /**
         * Sets the key to use in the $_SESSION array
         *
         * @param string $ns
         * @return \Barryvdh\Debugbar\LaravelDebugbar 
         * @static 
         */        public static function setStackDataSessionNamespace($ns)
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->setStackDataSessionNamespace($ns);
        }
                    /**
         * Returns the key used in the $_SESSION array
         *
         * @return string 
         * @static 
         */        public static function getStackDataSessionNamespace()
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->getStackDataSessionNamespace();
        }
                    /**
         * Sets whether to only use the session to store stacked data even
         * if a storage is enabled
         *
         * @param boolean $enabled
         * @return \Barryvdh\Debugbar\LaravelDebugbar 
         * @static 
         */        public static function setStackAlwaysUseSessionStorage($enabled = true)
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->setStackAlwaysUseSessionStorage($enabled);
        }
                    /**
         * Checks if the session is always used to store stacked data
         * even if a storage is enabled
         *
         * @return boolean 
         * @static 
         */        public static function isStackAlwaysUseSessionStorage()
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->isStackAlwaysUseSessionStorage();
        }
                    /**
         * 
         *
         * @static 
         */        public static function offsetSet($key, $value)
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->offsetSet($key, $value);
        }
                    /**
         * 
         *
         * @static 
         */        public static function offsetGet($key)
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->offsetGet($key);
        }
                    /**
         * 
         *
         * @static 
         */        public static function offsetExists($key)
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->offsetExists($key);
        }
                    /**
         * 
         *
         * @static 
         */        public static function offsetUnset($key)
        {            //Method inherited from \DebugBar\DebugBar         
                        /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
                        return $instance->offsetUnset($key);
        }
            }
    }

namespace Lorisleiva\Actions\Facades {
            /**
     * 
     *
     * @see ActionManager
     */        class Actions {
                    /**
         * 
         *
         * @param \Lorisleiva\Actions\class-string<JobDecorator> $jobDecoratorClass
         * @static 
         */        public static function useJobDecorator($jobDecoratorClass)
        {
                        return \Lorisleiva\Actions\ActionManager::useJobDecorator($jobDecoratorClass);
        }
                    /**
         * 
         *
         * @param \Lorisleiva\Actions\class-string<JobDecorator&ShouldBeUnique> $uniqueJobDecoratorClass
         * @static 
         */        public static function useUniqueJobDecorator($uniqueJobDecoratorClass)
        {
                        return \Lorisleiva\Actions\ActionManager::useUniqueJobDecorator($uniqueJobDecoratorClass);
        }
                    /**
         * 
         *
         * @static 
         */        public static function setBacktraceLimit($backtraceLimit)
        {
                        /** @var \Lorisleiva\Actions\ActionManager $instance */
                        return $instance->setBacktraceLimit($backtraceLimit);
        }
                    /**
         * 
         *
         * @static 
         */        public static function setDesignPatterns($designPatterns)
        {
                        /** @var \Lorisleiva\Actions\ActionManager $instance */
                        return $instance->setDesignPatterns($designPatterns);
        }
                    /**
         * 
         *
         * @static 
         */        public static function getDesignPatterns()
        {
                        /** @var \Lorisleiva\Actions\ActionManager $instance */
                        return $instance->getDesignPatterns();
        }
                    /**
         * 
         *
         * @static 
         */        public static function registerDesignPattern($designPattern)
        {
                        /** @var \Lorisleiva\Actions\ActionManager $instance */
                        return $instance->registerDesignPattern($designPattern);
        }
                    /**
         * 
         *
         * @static 
         */        public static function getDesignPatternsMatching($usedTraits)
        {
                        /** @var \Lorisleiva\Actions\ActionManager $instance */
                        return $instance->getDesignPatternsMatching($usedTraits);
        }
                    /**
         * 
         *
         * @static 
         */        public static function extend($app, $abstract)
        {
                        /** @var \Lorisleiva\Actions\ActionManager $instance */
                        return $instance->extend($app, $abstract);
        }
                    /**
         * 
         *
         * @static 
         */        public static function isExtending($abstract)
        {
                        /** @var \Lorisleiva\Actions\ActionManager $instance */
                        return $instance->isExtending($abstract);
        }
                    /**
         * 
         *
         * @static 
         */        public static function shouldExtend($abstract)
        {
                        /** @var \Lorisleiva\Actions\ActionManager $instance */
                        return $instance->shouldExtend($abstract);
        }
                    /**
         * 
         *
         * @static 
         */        public static function identifyAndDecorate($instance)
        {
                        /** @var \Lorisleiva\Actions\ActionManager $instance */
                        return $instance->identifyAndDecorate($instance);
        }
                    /**
         * 
         *
         * @static 
         */        public static function identifyFromBacktrace($usedTraits, $frame = null)
        {
                        /** @var \Lorisleiva\Actions\ActionManager $instance */
                        return $instance->identifyFromBacktrace($usedTraits, $frame);
        }
                    /**
         * 
         *
         * @static 
         */        public static function registerRoutes($paths = 'app/Actions')
        {
                        /** @var \Lorisleiva\Actions\ActionManager $instance */
                        return $instance->registerRoutes($paths);
        }
                    /**
         * 
         *
         * @static 
         */        public static function registerCommands($paths = 'app/Actions')
        {
                        /** @var \Lorisleiva\Actions\ActionManager $instance */
                        return $instance->registerCommands($paths);
        }
                    /**
         * 
         *
         * @static 
         */        public static function registerRoutesForAction($className)
        {
                        /** @var \Lorisleiva\Actions\ActionManager $instance */
                        return $instance->registerRoutesForAction($className);
        }
                    /**
         * 
         *
         * @static 
         */        public static function registerCommandsForAction($className)
        {
                        /** @var \Lorisleiva\Actions\ActionManager $instance */
                        return $instance->registerCommandsForAction($className);
        }
            }
    }

namespace Lorisleiva\Lody {
            /**
     * 
     *
     * @see LodyManager
     */        class Lody {
                    /**
         * 
         *
         * @static 
         */        public static function classes($paths, $recursive = true)
        {
                        /** @var \Lorisleiva\Lody\LodyManager $instance */
                        return $instance->classes($paths, $recursive);
        }
                    /**
         * 
         *
         * @static 
         */        public static function classesFromFinder($finder)
        {
                        /** @var \Lorisleiva\Lody\LodyManager $instance */
                        return $instance->classesFromFinder($finder);
        }
                    /**
         * 
         *
         * @static 
         */        public static function files($paths, $recursive = true, $hidden = false)
        {
                        /** @var \Lorisleiva\Lody\LodyManager $instance */
                        return $instance->files($paths, $recursive, $hidden);
        }
                    /**
         * 
         *
         * @static 
         */        public static function filesFromFinder($finder)
        {
                        /** @var \Lorisleiva\Lody\LodyManager $instance */
                        return $instance->filesFromFinder($finder);
        }
                    /**
         * 
         *
         * @static 
         */        public static function resolvePathUsing($callback)
        {
                        /** @var \Lorisleiva\Lody\LodyManager $instance */
                        return $instance->resolvePathUsing($callback);
        }
                    /**
         * 
         *
         * @static 
         */        public static function resolvePath($path)
        {
                        /** @var \Lorisleiva\Lody\LodyManager $instance */
                        return $instance->resolvePath($path);
        }
                    /**
         * 
         *
         * @static 
         */        public static function resolveClassnameUsing($callback)
        {
                        /** @var \Lorisleiva\Lody\LodyManager $instance */
                        return $instance->resolveClassnameUsing($callback);
        }
                    /**
         * 
         *
         * @static 
         */        public static function resolveClassname($file)
        {
                        /** @var \Lorisleiva\Lody\LodyManager $instance */
                        return $instance->resolveClassname($file);
        }
                    /**
         * 
         *
         * @static 
         */        public static function setBasePath($basePath)
        {
                        /** @var \Lorisleiva\Lody\LodyManager $instance */
                        return $instance->setBasePath($basePath);
        }
                    /**
         * 
         *
         * @static 
         */        public static function getBasePath($path = '')
        {
                        /** @var \Lorisleiva\Lody\LodyManager $instance */
                        return $instance->getBasePath($path);
        }
                    /**
         * 
         *
         * @static 
         */        public static function setAutoloadPath($autoloadPath)
        {
                        /** @var \Lorisleiva\Lody\LodyManager $instance */
                        return $instance->setAutoloadPath($autoloadPath);
        }
                    /**
         * 
         *
         * @static 
         */        public static function getAutoloadPath()
        {
                        /** @var \Lorisleiva\Lody\LodyManager $instance */
                        return $instance->getAutoloadPath();
        }
            }
    }

namespace Nwidart\Modules\Facades {
            /**
     * 
     *
     */        class Module {
                    /**
         * Add other module location.
         *
         * @param string $path
         * @return \Nwidart\Modules\Laravel\LaravelFileRepository 
         * @static 
         */        public static function addLocation($path)
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->addLocation($path);
        }
                    /**
         * Get all additional paths.
         *
         * @return array 
         * @static 
         */        public static function getPaths()
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->getPaths();
        }
                    /**
         * Get scanned modules paths.
         *
         * @return array 
         * @static 
         */        public static function getScanPaths()
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->getScanPaths();
        }
                    /**
         * Get & scan all modules.
         *
         * @return array 
         * @static 
         */        public static function scan()
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->scan();
        }
                    /**
         * Get all modules.
         *
         * @return array 
         * @static 
         */        public static function all()
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->all();
        }
                    /**
         * Get cached modules.
         *
         * @return array 
         * @static 
         */        public static function getCached()
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->getCached();
        }
                    /**
         * Get all modules as collection instance.
         *
         * @return \Nwidart\Modules\Collection 
         * @static 
         */        public static function toCollection()
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->toCollection();
        }
                    /**
         * Get modules by status.
         *
         * @param $status
         * @return array 
         * @static 
         */        public static function getByStatus($status)
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->getByStatus($status);
        }
                    /**
         * Determine whether the given module exist.
         *
         * @param $name
         * @return bool 
         * @static 
         */        public static function has($name)
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->has($name);
        }
                    /**
         * Get list of enabled modules.
         *
         * @return array 
         * @static 
         */        public static function allEnabled()
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->allEnabled();
        }
                    /**
         * Get list of disabled modules.
         *
         * @return array 
         * @static 
         */        public static function allDisabled()
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->allDisabled();
        }
                    /**
         * Get count from all modules.
         *
         * @return int 
         * @static 
         */        public static function count()
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->count();
        }
                    /**
         * Get all ordered modules.
         *
         * @param string $direction
         * @return array 
         * @static 
         */        public static function getOrdered($direction = 'asc')
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->getOrdered($direction);
        }
                    /**
         * 
         *
         * @inheritDoc 
         * @static 
         */        public static function getPath()
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->getPath();
        }
                    /**
         * 
         *
         * @inheritDoc 
         * @static 
         */        public static function register()
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->register();
        }
                    /**
         * 
         *
         * @inheritDoc 
         * @static 
         */        public static function boot()
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->boot();
        }
                    /**
         * 
         *
         * @inheritDoc 
         * @static 
         */        public static function find($name)
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->find($name);
        }
                    /**
         * Find a specific module, if there return that, otherwise throw exception.
         *
         * @param $name
         * @return \Module 
         * @throws ModuleNotFoundException
         * @static 
         */        public static function findOrFail($name)
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->findOrFail($name);
        }
                    /**
         * Get all modules as laravel collection instance.
         *
         * @param $status
         * @return \Nwidart\Modules\Collection 
         * @static 
         */        public static function collections($status = 1)
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->collections($status);
        }
                    /**
         * Get module path for a specific module.
         *
         * @param $module
         * @return string 
         * @static 
         */        public static function getModulePath($module)
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->getModulePath($module);
        }
                    /**
         * 
         *
         * @inheritDoc 
         * @static 
         */        public static function assetPath($module)
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->assetPath($module);
        }
                    /**
         * 
         *
         * @inheritDoc 
         * @static 
         */        public static function config($key, $default = null)
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->config($key, $default);
        }
                    /**
         * Get storage path for module used.
         *
         * @return string 
         * @static 
         */        public static function getUsedStoragePath()
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->getUsedStoragePath();
        }
                    /**
         * Set module used for cli session.
         *
         * @param $name
         * @throws ModuleNotFoundException
         * @static 
         */        public static function setUsed($name)
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->setUsed($name);
        }
                    /**
         * Forget the module used for cli session.
         *
         * @static 
         */        public static function forgetUsed()
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->forgetUsed();
        }
                    /**
         * Get module used for cli session.
         *
         * @return string 
         * @throws \Nwidart\Modules\Exceptions\ModuleNotFoundException
         * @static 
         */        public static function getUsedNow()
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->getUsedNow();
        }
                    /**
         * Get laravel filesystem instance.
         *
         * @return \Nwidart\Modules\Filesystem 
         * @static 
         */        public static function getFiles()
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->getFiles();
        }
                    /**
         * Get module assets path.
         *
         * @return string 
         * @static 
         */        public static function getAssetsPath()
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->getAssetsPath();
        }
                    /**
         * Get asset url from a specific module.
         *
         * @param string $asset
         * @return string 
         * @throws InvalidAssetPath
         * @static 
         */        public static function asset($asset)
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->asset($asset);
        }
                    /**
         * 
         *
         * @inheritDoc 
         * @static 
         */        public static function isEnabled($name)
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->isEnabled($name);
        }
                    /**
         * 
         *
         * @inheritDoc 
         * @static 
         */        public static function isDisabled($name)
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->isDisabled($name);
        }
                    /**
         * Enabling a specific module.
         *
         * @param string $name
         * @return void 
         * @throws \Nwidart\Modules\Exceptions\ModuleNotFoundException
         * @static 
         */        public static function enable($name)
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        $instance->enable($name);
        }
                    /**
         * Disabling a specific module.
         *
         * @param string $name
         * @return void 
         * @throws \Nwidart\Modules\Exceptions\ModuleNotFoundException
         * @static 
         */        public static function disable($name)
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        $instance->disable($name);
        }
                    /**
         * 
         *
         * @inheritDoc 
         * @static 
         */        public static function delete($name)
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->delete($name);
        }
                    /**
         * Update dependencies for the specified module.
         *
         * @param string $module
         * @static 
         */        public static function update($module)
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->update($module);
        }
                    /**
         * Install the specified module.
         *
         * @param string $name
         * @param string $version
         * @param string $type
         * @param bool $subtree
         * @return \Symfony\Component\Process\Process 
         * @static 
         */        public static function install($name, $version = 'dev-master', $type = 'composer', $subtree = false)
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->install($name, $version, $type, $subtree);
        }
                    /**
         * Get stub path.
         *
         * @return string|null 
         * @static 
         */        public static function getStubPath()
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->getStubPath();
        }
                    /**
         * Set stub path.
         *
         * @param string $stubPath
         * @return \Nwidart\Modules\Laravel\LaravelFileRepository 
         * @static 
         */        public static function setStubPath($stubPath)
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        /** @var \Nwidart\Modules\Laravel\LaravelFileRepository $instance */
                        return $instance->setStubPath($stubPath);
        }
                    /**
         * Register a custom macro.
         *
         * @param string $name
         * @param object|callable $macro
         * @param-closure-this static  $macro
         * @return void 
         * @static 
         */        public static function macro($name, $macro)
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        \Nwidart\Modules\Laravel\LaravelFileRepository::macro($name, $macro);
        }
                    /**
         * Mix another object into the class.
         *
         * @param object $mixin
         * @param bool $replace
         * @return void 
         * @throws \ReflectionException
         * @static 
         */        public static function mixin($mixin, $replace = true)
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        \Nwidart\Modules\Laravel\LaravelFileRepository::mixin($mixin, $replace);
        }
                    /**
         * Checks if macro is registered.
         *
         * @param string $name
         * @return bool 
         * @static 
         */        public static function hasMacro($name)
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        return \Nwidart\Modules\Laravel\LaravelFileRepository::hasMacro($name);
        }
                    /**
         * Flush the existing macros.
         *
         * @return void 
         * @static 
         */        public static function flushMacros()
        {            //Method inherited from \Nwidart\Modules\FileRepository         
                        \Nwidart\Modules\Laravel\LaravelFileRepository::flushMacros();
        }
            }
    }

namespace Stancl\Tenancy\Facades {
            /**
     * 
     *
     */        class Tenancy {
                    /**
         * Initializes the tenant.
         *
         * @param \Stancl\Tenancy\Contracts\Tenant|int|string $tenant
         * @return void 
         * @static 
         */        public static function initialize($tenant)
        {
                        /** @var \Stancl\Tenancy\Tenancy $instance */
                        $instance->initialize($tenant);
        }
                    /**
         * 
         *
         * @static 
         */        public static function end()
        {
                        /** @var \Stancl\Tenancy\Tenancy $instance */
                        return $instance->end();
        }
                    /**
         * 
         *
         * @return \Stancl\Tenancy\Contracts\TenancyBootstrapper[] 
         * @static 
         */        public static function getBootstrappers()
        {
                        /** @var \Stancl\Tenancy\Tenancy $instance */
                        return $instance->getBootstrappers();
        }
                    /**
         * 
         *
         * @static 
         */        public static function query()
        {
                        /** @var \Stancl\Tenancy\Tenancy $instance */
                        return $instance->query();
        }
                    /**
         * 
         *
         * @return \Stancl\Tenancy\Contracts\Tenant|\Illuminate\Database\Eloquent\Model 
         * @static 
         */        public static function model()
        {
                        /** @var \Stancl\Tenancy\Tenancy $instance */
                        return $instance->model();
        }
                    /**
         * 
         *
         * @static 
         */        public static function find($id)
        {
                        /** @var \Stancl\Tenancy\Tenancy $instance */
                        return $instance->find($id);
        }
                    /**
         * Run a callback in the central context.
         * 
         * Atomic, safely reverts to previous context.
         *
         * @param callable $callback
         * @return mixed 
         * @static 
         */        public static function central($callback)
        {
                        /** @var \Stancl\Tenancy\Tenancy $instance */
                        return $instance->central($callback);
        }
                    /**
         * Run a callback for multiple tenants.
         * 
         * More performant than running $tenant->run() one by one.
         *
         * @param \Stancl\Tenancy\Contracts\Tenant[]|\Traversable|string[]|null $tenants
         * @param callable $callback
         * @return void 
         * @static 
         */        public static function runForMultiple($tenants, $callback)
        {
                        /** @var \Stancl\Tenancy\Tenancy $instance */
                        $instance->runForMultiple($tenants, $callback);
        }
                    /**
         * Register a custom macro.
         *
         * @param string $name
         * @param object|callable $macro
         * @param-closure-this static  $macro
         * @return void 
         * @static 
         */        public static function macro($name, $macro)
        {
                        \Stancl\Tenancy\Tenancy::macro($name, $macro);
        }
                    /**
         * Mix another object into the class.
         *
         * @param object $mixin
         * @param bool $replace
         * @return void 
         * @throws \ReflectionException
         * @static 
         */        public static function mixin($mixin, $replace = true)
        {
                        \Stancl\Tenancy\Tenancy::mixin($mixin, $replace);
        }
                    /**
         * Checks if macro is registered.
         *
         * @param string $name
         * @return bool 
         * @static 
         */        public static function hasMacro($name)
        {
                        return \Stancl\Tenancy\Tenancy::hasMacro($name);
        }
                    /**
         * Flush the existing macros.
         *
         * @return void 
         * @static 
         */        public static function flushMacros()
        {
                        \Stancl\Tenancy\Tenancy::flushMacros();
        }
            }
            /**
     * 
     *
     */        class GlobalCache {
                    /**
         * Get a cache store instance by name, wrapped in a repository.
         *
         * @param string|null $name
         * @return \Illuminate\Contracts\Cache\Repository 
         * @static 
         */        public static function store($name = null)
        {
                        /** @var \Illuminate\Cache\CacheManager $instance */
                        return $instance->store($name);
        }
                    /**
         * Get a cache driver instance.
         *
         * @param string|null $driver
         * @return \Illuminate\Contracts\Cache\Repository 
         * @static 
         */        public static function driver($driver = null)
        {
                        /** @var \Illuminate\Cache\CacheManager $instance */
                        return $instance->driver($driver);
        }
                    /**
         * Resolve the given store.
         *
         * @param string $name
         * @return \Illuminate\Contracts\Cache\Repository 
         * @throws \InvalidArgumentException
         * @static 
         */        public static function resolve($name)
        {
                        /** @var \Illuminate\Cache\CacheManager $instance */
                        return $instance->resolve($name);
        }
                    /**
         * Create a new cache repository with the given implementation.
         *
         * @param \Illuminate\Contracts\Cache\Store $store
         * @param array $config
         * @return \Illuminate\Cache\Repository 
         * @static 
         */        public static function repository($store, $config = [])
        {
                        /** @var \Illuminate\Cache\CacheManager $instance */
                        return $instance->repository($store, $config);
        }
                    /**
         * Re-set the event dispatcher on all resolved cache repositories.
         *
         * @return void 
         * @static 
         */        public static function refreshEventDispatcher()
        {
                        /** @var \Illuminate\Cache\CacheManager $instance */
                        $instance->refreshEventDispatcher();
        }
                    /**
         * Get the default cache driver name.
         *
         * @return string 
         * @static 
         */        public static function getDefaultDriver()
        {
                        /** @var \Illuminate\Cache\CacheManager $instance */
                        return $instance->getDefaultDriver();
        }
                    /**
         * Set the default cache driver name.
         *
         * @param string $name
         * @return void 
         * @static 
         */        public static function setDefaultDriver($name)
        {
                        /** @var \Illuminate\Cache\CacheManager $instance */
                        $instance->setDefaultDriver($name);
        }
                    /**
         * Unset the given driver instances.
         *
         * @param array|string|null $name
         * @return \Illuminate\Cache\CacheManager 
         * @static 
         */        public static function forgetDriver($name = null)
        {
                        /** @var \Illuminate\Cache\CacheManager $instance */
                        return $instance->forgetDriver($name);
        }
                    /**
         * Disconnect the given driver and remove from local cache.
         *
         * @param string|null $name
         * @return void 
         * @static 
         */        public static function purge($name = null)
        {
                        /** @var \Illuminate\Cache\CacheManager $instance */
                        $instance->purge($name);
        }
                    /**
         * Register a custom driver creator Closure.
         *
         * @param string $driver
         * @param \Closure $callback
         * @return \Illuminate\Cache\CacheManager 
         * @static 
         */        public static function extend($driver, $callback)
        {
                        /** @var \Illuminate\Cache\CacheManager $instance */
                        return $instance->extend($driver, $callback);
        }
                    /**
         * Set the application instance used by the manager.
         *
         * @param \Illuminate\Contracts\Foundation\Application $app
         * @return \Illuminate\Cache\CacheManager 
         * @static 
         */        public static function setApplication($app)
        {
                        /** @var \Illuminate\Cache\CacheManager $instance */
                        return $instance->setApplication($app);
        }
            }
    }

namespace Orion\Facades {
            /**
     * 
     *
     */        class Orion {
                    /**
         * Registers new standard resource.
         *
         * @param string $name
         * @param string $controller
         * @param array $options
         * @return \Illuminate\Routing\PendingResourceRegistration 
         * @throws BindingResolutionException
         * @static 
         */        public static function resource($name, $controller, $options = [])
        {
                        /** @var \Orion\Orion $instance */
                        return $instance->resource($name, $controller, $options);
        }
                    /**
         * Register new resource for "hasOne" relation.
         *
         * @param string $resource
         * @param string $relation
         * @param string $controller
         * @param array $options
         * @return \Illuminate\Routing\PendingResourceRegistration 
         * @throws BindingResolutionException
         * @static 
         */        public static function hasOneResource($resource, $relation, $controller, $options = [])
        {
                        /** @var \Orion\Orion $instance */
                        return $instance->hasOneResource($resource, $relation, $controller, $options);
        }
                    /**
         * Register new resource for "belongsTo" relation.
         *
         * @param string $resource
         * @param string $relation
         * @param string $controller
         * @param array $options
         * @return \Orion\Http\Routing\PendingResourceRegistration 
         * @throws BindingResolutionException
         * @static 
         */        public static function belongsToResource($resource, $relation, $controller, $options = [])
        {
                        /** @var \Orion\Orion $instance */
                        return $instance->belongsToResource($resource, $relation, $controller, $options);
        }
                    /**
         * Register new resource for "hasMany" relation.
         *
         * @param string $resource
         * @param string $relation
         * @param string $controller
         * @param array $options
         * @return \Orion\Http\Routing\PendingResourceRegistration 
         * @throws BindingResolutionException
         * @static 
         */        public static function hasManyResource($resource, $relation, $controller, $options = [])
        {
                        /** @var \Orion\Orion $instance */
                        return $instance->hasManyResource($resource, $relation, $controller, $options);
        }
                    /**
         * Register new resource for "belongsToMany" relation.
         *
         * @param string $resource
         * @param string $relation
         * @param string $controller
         * @param array $options
         * @return \Orion\Http\Routing\PendingResourceRegistration 
         * @throws BindingResolutionException
         * @static 
         */        public static function belongsToManyResource($resource, $relation, $controller, $options = [])
        {
                        /** @var \Orion\Orion $instance */
                        return $instance->belongsToManyResource($resource, $relation, $controller, $options);
        }
                    /**
         * Register new resource for "hasOneThrough" relation.
         *
         * @param string $resource
         * @param string $relation
         * @param string $controller
         * @param array $options
         * @return \Orion\Http\Routing\PendingResourceRegistration 
         * @throws BindingResolutionException
         * @static 
         */        public static function hasOneThroughResource($resource, $relation, $controller, $options = [])
        {
                        /** @var \Orion\Orion $instance */
                        return $instance->hasOneThroughResource($resource, $relation, $controller, $options);
        }
                    /**
         * Register new resource for "hasManyThrough" relation.
         *
         * @param string $resource
         * @param string $relation
         * @param string $controller
         * @param array $options
         * @return \Orion\Http\Routing\PendingResourceRegistration 
         * @throws BindingResolutionException
         * @static 
         */        public static function hasManyThroughResource($resource, $relation, $controller, $options = [])
        {
                        /** @var \Orion\Orion $instance */
                        return $instance->hasManyThroughResource($resource, $relation, $controller, $options);
        }
                    /**
         * Register new resource for "morphOne" relation.
         *
         * @param string $resource
         * @param string $relation
         * @param string $controller
         * @param array $options
         * @return \Orion\Http\Routing\PendingResourceRegistration 
         * @throws BindingResolutionException
         * @static 
         */        public static function morphOneResource($resource, $relation, $controller, $options = [])
        {
                        /** @var \Orion\Orion $instance */
                        return $instance->morphOneResource($resource, $relation, $controller, $options);
        }
                    /**
         * Register new resource for "morphMany" relation.
         *
         * @param string $resource
         * @param string $relation
         * @param string $controller
         * @param array $options
         * @return \Orion\Http\Routing\PendingResourceRegistration 
         * @throws BindingResolutionException
         * @static 
         */        public static function morphManyResource($resource, $relation, $controller, $options = [])
        {
                        /** @var \Orion\Orion $instance */
                        return $instance->morphManyResource($resource, $relation, $controller, $options);
        }
                    /**
         * Register new resource for "morphTo" relation.
         *
         * @param string $resource
         * @param string $relation
         * @param string $controller
         * @param array $options
         * @return \Orion\Http\Routing\PendingResourceRegistration 
         * @throws BindingResolutionException
         * @static 
         */        public static function morphToResource($resource, $relation, $controller, $options = [])
        {
                        /** @var \Orion\Orion $instance */
                        return $instance->morphToResource($resource, $relation, $controller, $options);
        }
                    /**
         * Register new resource for "morphToMany" relation.
         *
         * @param string $resource
         * @param string $relation
         * @param string $controller
         * @param array $options
         * @return \Orion\Http\Routing\PendingResourceRegistration 
         * @throws BindingResolutionException
         * @static 
         */        public static function morphToManyResource($resource, $relation, $controller, $options = [])
        {
                        /** @var \Orion\Orion $instance */
                        return $instance->morphToManyResource($resource, $relation, $controller, $options);
        }
                    /**
         * Register new resource for "morphedByMany" relation.
         *
         * @param string $resource
         * @param string $relation
         * @param string $controller
         * @param array $options
         * @return \Orion\Http\Routing\PendingResourceRegistration 
         * @throws BindingResolutionException
         * @static 
         */        public static function morphedByManyResource($resource, $relation, $controller, $options = [])
        {
                        /** @var \Orion\Orion $instance */
                        return $instance->morphedByManyResource($resource, $relation, $controller, $options);
        }
            }
    }

namespace Illuminate\Support {
            /**
     * 
     *
     * @template TKey of array-key
     * @template-covariant TValue
     * @implements \ArrayAccess<TKey, TValue>
     * @implements \Illuminate\Support\Enumerable<TKey, TValue>
     */        class Collection {
                    /**
         * 
         *
         * @see \Barryvdh\Debugbar\ServiceProvider::register()
         * @static 
         */        public static function debug()
        {
                        return \Illuminate\Support\Collection::debug();
        }
                    /**
         * 
         *
         * @see \Maatwebsite\Excel\Mixins\DownloadCollectionMixin::downloadExcel()
         * @param string $fileName
         * @param string|null $writerType
         * @param mixed $withHeadings
         * @param array $responseHeaders
         * @static 
         */        public static function downloadExcel($fileName, $writerType = null, $withHeadings = false, $responseHeaders = [])
        {
                        return \Illuminate\Support\Collection::downloadExcel($fileName, $writerType, $withHeadings, $responseHeaders);
        }
                    /**
         * 
         *
         * @see \Maatwebsite\Excel\Mixins\StoreCollectionMixin::storeExcel()
         * @param string $filePath
         * @param string|null $disk
         * @param string|null $writerType
         * @param mixed $withHeadings
         * @static 
         */        public static function storeExcel($filePath, $disk = null, $writerType = null, $withHeadings = false)
        {
                        return \Illuminate\Support\Collection::storeExcel($filePath, $disk, $writerType, $withHeadings);
        }
            }
    }

namespace Illuminate\Http {
            /**
     * 
     *
     */        class Request {
                    /**
         * 
         *
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestValidation()
         * @param array $rules
         * @param mixed $params
         * @static 
         */        public static function validate($rules, ...$params)
        {
                        return \Illuminate\Http\Request::validate($rules, ...$params);
        }
                    /**
         * 
         *
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestValidation()
         * @param string $errorBag
         * @param array $rules
         * @param mixed $params
         * @static 
         */        public static function validateWithBag($errorBag, $rules, ...$params)
        {
                        return \Illuminate\Http\Request::validateWithBag($errorBag, $rules, ...$params);
        }
                    /**
         * 
         *
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestSignatureValidation()
         * @param mixed $absolute
         * @static 
         */        public static function hasValidSignature($absolute = true)
        {
                        return \Illuminate\Http\Request::hasValidSignature($absolute);
        }
                    /**
         * 
         *
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestSignatureValidation()
         * @static 
         */        public static function hasValidRelativeSignature()
        {
                        return \Illuminate\Http\Request::hasValidRelativeSignature();
        }
                    /**
         * 
         *
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestSignatureValidation()
         * @param mixed $ignoreQuery
         * @param mixed $absolute
         * @static 
         */        public static function hasValidSignatureWhileIgnoring($ignoreQuery = [], $absolute = true)
        {
                        return \Illuminate\Http\Request::hasValidSignatureWhileIgnoring($ignoreQuery, $absolute);
        }
                    /**
         * 
         *
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestSignatureValidation()
         * @param mixed $ignoreQuery
         * @static 
         */        public static function hasValidRelativeSignatureWhileIgnoring($ignoreQuery = [])
        {
                        return \Illuminate\Http\Request::hasValidRelativeSignatureWhileIgnoring($ignoreQuery);
        }
            }
    }

namespace Illuminate\Database\Eloquent\Factories {
            /**
     * 
     *
     * @template TModel of \Illuminate\Database\Eloquent\Model
     * @method $this trashed()
     */        class Factory {
                    /**
         * 
         *
         * @see \Spatie\Translatable\TranslatableServiceProvider::packageRegistered()
         * @param array|string $locales
         * @param mixed|null $value
         * @static 
         */        public static function translations($locales, $value)
        {
                        return \Illuminate\Database\Eloquent\Factories\Factory::translations($locales, $value);
        }
            }
    }

namespace Illuminate\Routing {
            /**
     * 
     *
     */        class Route {
                    /**
         * 
         *
         * @see \Spatie\Permission\PermissionServiceProvider::registerMacroHelpers()
         * @param mixed $roles
         * @static 
         */        public static function role($roles = [])
        {
                        return \Illuminate\Routing\Route::role($roles);
        }
                    /**
         * 
         *
         * @see \Spatie\Permission\PermissionServiceProvider::registerMacroHelpers()
         * @param mixed $permissions
         * @static 
         */        public static function permission($permissions = [])
        {
                        return \Illuminate\Routing\Route::permission($permissions);
        }
            }
    }

namespace Nwidart\Modules {
            /**
     * 
     *
     */        class Collection {
            }
    }


namespace  {
            class Excel extends \Maatwebsite\Excel\Facades\Excel {}
            class Debugbar extends \Barryvdh\Debugbar\Facades\Debugbar {}
            class Action extends \Lorisleiva\Actions\Facades\Actions {}
            class Lody extends \Lorisleiva\Lody\Lody {}
            class Module extends \Nwidart\Modules\Facades\Module {}
            class Tenancy extends \Stancl\Tenancy\Facades\Tenancy {}
            class GlobalCache extends \Stancl\Tenancy\Facades\GlobalCache {}
            class Orion extends \Orion\Facades\Orion {}
    }





