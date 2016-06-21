<?php
use Symfony\Component\Yaml\Yaml;
return Yaml::parse(file_get_contents(base_path().'/config/adminstrative_area.yml'));