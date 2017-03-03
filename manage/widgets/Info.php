<?php

namespace manage\widgets;


class Info extends DashboardWidget
{
    public function run()
    {
        return $this->render('info',
            [
                'height' => $this->height,
                'width' => $this->width,
                'position' => $this->position,
            ]);
    }
}