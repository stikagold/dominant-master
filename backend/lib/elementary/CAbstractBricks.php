<?php

    namespace lib\elementary;

    /**
     * Interface IBricks
     *
     * @package lib\elementary
     */
    interface IBricks{

        /**
         * @param array|null $args
         *
         * @return mixed
         */
        public function Initial(array $args=null);

        /**
         * @param $key
         * @param null $in_area
         * @return CResponse
         */
        public function getBlock($key, $in_area = null);

        /**
         * @param $key
         * @param $value
         * @param null $in_area
         * @return CResponse
         */
        public function setBlock($key, $value, $in_area = null);
    }

    /**
     * Class CAbstractBricks
     * @package lib\elementary
     */
    class CAbstractBricks implements IBricks {

        protected $storage = [];

        /**
         * @param array|null $args
         * @return void
         */
        public function Initial (array $args = null) {
            if ($args) {
                $this->storage = $args;
            }
        }

        /**
         * @param $key
         * @param null $in_area
         * @return CResponse
         */
        public function getBlock ($key, $in_area = null) {
            $response = new CResponse();
            if($in_area){
                (isset($this->storage[$in_area][$key]))?
                    $response->setData($this->storage[$in_area][$key]): $response->setResponseStatus(false);
            }
            else{
                (isset($this->storage[$key]))?
                    $response->setData($this->storage[$key]): $response->setResponseStatus(false);
            }
            return $response;
        }

        /**
         * @param $key
         * @param $value
         * @param null $in_area
         * @return void
         */
        public function setBlock ($key, $value, $in_area = null) {
            ($in_area)? $this->storage[$in_area][$key] = $value: $this->storage[$key][$value];
        }

        /**
         * @return CResponse
         */
        public function getStorage(){
            $return = new CResponse($this->storage);
            return $return;
        }

        //TODO - Do recursive search for key
/*        private function walkForKey($key, array $blocks, $onAllBlock = false){
            $response = new CResponse();
            if($onAllBlock){
                foreach ($block as $cur_key=>$values){

                }
            }
            else{
                if(isset($block[$key])){
                    $response->setData([$key=>$block[$key]]);
                    $response->setResponseStatus(true);
                }
                else $response->setResponseStatus(false);
            }
            return $response;
        }*/
    }