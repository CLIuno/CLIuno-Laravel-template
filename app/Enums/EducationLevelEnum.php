<?php

namespace App\Enums;

enum EducationLevelEnum: string
{
    case ElementaryOrLess = 'elementaryOrLess';
    case Intermediate = 'intermediate';
    case HighSchool = 'highSchool';
    case Diploma = 'diploma';
    case Bachelor = 'bachelor';
    case Master = 'master';
    case Doctorate = 'doctorate';
}
