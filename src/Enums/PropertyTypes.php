<?php

namespace Leveon\Connector\Enums;

enum PropertyTypes: int{
	case ShortText = 1;
	case Boolean = 2;
    case Integer = 3;
	case LongText = 5;
	case DecimalWithUnit = 7;
	case Image = 8;
	case File = 9;
}