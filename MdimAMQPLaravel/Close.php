<?php

namespace MdimAMQPLaravel;

trait Close {
    public function close($reply_code = 0, $reply_text = '', $method_sig = array(0, 0)) {
        $this->channel->close($reply_code, $reply_text, $method_sig);
        $this->connection->close($reply_code, $reply_text, $method_sig);
    }
}
