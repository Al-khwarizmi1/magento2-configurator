<?php

namespace CtiDigital\Configurator\Model;

use CtiDigital\Configurator\Model\Component\ComponentAbstract;
use CtiDigital\Configurator\Model\Exception\ComponentException;
use Symfony\Component\Yaml\Parser;

class Processor
{

    /**
     * @var string
     */
    protected $environment;

    /**
     * @var array
     */
    protected $components = array();

    /**
     * @var mixed
     */
    protected $logLevel;


    /**
     * @param string $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * @param string $component
     */
    public function addComponent($component)
    {
        try {
            if (!$this->isValidComponent($component)) {
                throw new ComponentException(
                    sprintf('%s component does not appear to be a valid component.', $component)
                );
            }

            $this->components[$component] = $this->mapComponentNameToClass($component);
        } catch (ComponentException $e) {
            throw $e;
        }
    }

    /**
     * @param mixed $logLevel
     *
     * @todo Introduce log levels to provide more tidy CLI console logging. Usage of PHP CLImate.
     */
    public function setLogLevel($logLevel)
    {
        $this->logLevel = $logLevel;
    }


    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @return array
     */
    public function getComponents()
    {
        return $this->components;
    }

    /**
     * @return mixed
     */
    public function getLogLevel()
    {
        return $this->logLevel;
    }

    /**
     * Run the components individually
     */
    public function run()
    {
        // If the components list is empty, then the user would want to run all components in the master.yaml
        if (empty($this->components)) {

            try {

                // Read master yaml
                $masterPath = BP . '/app/etc/master.yaml';
                if (!file_exists($masterPath)) {
                    throw new ComponentException("Master YAML does not exist. Please create one in $masterPath");
                }

                $yamlContents = file_get_contents($masterPath);

                $yaml = new Parser();

                $data = $yaml->parse($yamlContents);

                print_r($data);
            // Validate master yaml
            // Loop through components and run them individually in the master.yaml order
            // Include any other attributes that comes through the master.yaml

            } catch (ComponentException $e) {
                echo $e->getMessage();
            }

        } else {

            // Loop through the specified components
            foreach ($this->components as $componentClass) {

                // Find component in the master.yaml and its associated settings
                $source = '';

                /* @var $component ComponentAbstract */
                $component = new $componentClass;
                $component->setSource($source)->process();

            }
        }
    }

    /**
     * @todo validate the component to see it actually exists
     *
     * @param $component
     * @return bool
     */
    private function isValidComponent($component)
    {
        if ($component) {
            return true;
        }
    }

}
