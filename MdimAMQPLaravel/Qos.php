<?php

namespace MdimAMQPLaravel;

trait Qos {
    public function qos($prefetch_size, $prefetch_count, $a_global)
    {
        $this->channel->basic_qos($prefetch_size, $prefetch_count, $a_global);
        
        return $this;
    }
}
