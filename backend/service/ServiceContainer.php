<?php
    class ContainerServices {
        private $services = [];
        private $instances = [];

        public function registerService($services, callable $callable) {
            $this->services[$services] = $callable;
        }

        public function getService($service) {
            if (!isset($this->instances[$service])) {
                //if(empty($this->services[$service])) throw new Exception("Le service {$service} n'est pas enregistré.");
                if(!array_key_exists($service,$this->services)) throw new Exception("Le service {$service} n'est pas enregistré.");
                $this->instances[$service] = $this->services[$service]($this);
            }
            return $this->instances[$service];
        }

    }
?>