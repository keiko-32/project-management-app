<?php

namespace App\Enums;

enum ProjectStatus: string
{

    case UNTOUCHED = '未対応';
    case NEGOTIATING = '商談中';
    case PROPOSING = '提案中';
    case WON = '受注';
    case LOST = '失注';
}


