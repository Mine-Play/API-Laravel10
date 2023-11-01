<?php

namespace App\Enums;


enum Errors: int {
    case CLIENT_VALIDATION = 4001; // Validator Error
    case CLIENT_CREDENTIALS = 4002; // Invalid Password 
    case CLIENT_UNAUTHORIZED = 4003; // User Auth session is invalid
    case CLIENT_NOT_FOUND = 4004; // Unreachable resource
    case CLIENT_PIN = 4005; // Client 5-digit pin-code is invalid
}
