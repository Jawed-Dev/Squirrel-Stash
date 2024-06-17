<?php
    class ContainerServices {
        private $services = [];
        private $instances = [];

        public function registerService($services, callable $callable) {
            $this->services[$services] = $callable;
        }

        public function get($service) {
            if (empty($this->instances[$service])) {
                if (empty($this->services[$service])) {
                    throw new Exception("Le {$service} n'est pas enregistré.");
                }
                $this->instances[$service] = $this->services[$service]($this);
            }
            return $this->instances[$service];
        }

    }


?>