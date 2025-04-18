<?php

if (!function_exists('dump')) {
    function dump(...$args)
    {
        echo '<style>
                pre.dd-dump {
                    background-color: #282a36;
                    color: #f8f8f2;
                    padding: 15px;
                    border-radius: 5px;
                    overflow: auto;
                    font-size: 14px;
                    line-height: 1.5;
                }
                .dd-dump .dd-type { color: #bd93f9; }
                .dd-dump .dd-key { color: #8be9fd; }
                .dd-dump .dd-value { color: #50fa7b; }
                .dd-dump .dd-punct { color: #f8f8f2; }
                .dd-dump .dd-visibility { color: #ff79c6; }
            </style>';

        foreach ($args as $arg) {
            echo '<pre class="dd-dump">';
            dumpVariable($arg);
            echo '</pre>';
        }
    }
}

if (!function_exists('dd')) {
    function dd(...$args)
    {
        dump(...$args);
        exit();
    }
}

if (!function_exists('dumpVariable')) {
    function dumpVariable($var, $indent = 0, $showType = true)
    {
        $type = gettype($var);
        $indentation = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $indent);

        if ($var instanceof mysqli) {
            echo $indentation . '<span class="dd-type">mysqli Connection</span><br>';
            echo $indentation . '&nbsp;&nbsp;&nbsp;&nbsp;<span class="dd-key">Host</span>: <span class="dd-value">' . htmlspecialchars($var->host_info, ENT_QUOTES, 'UTF-8') . '</span><br>';
            echo $indentation . '&nbsp;&nbsp;&nbsp;&nbsp;<span class="dd-key">Versão do MySQL</span>: <span class="dd-value">' . htmlspecialchars($var->server_info, ENT_QUOTES, 'UTF-8') . '</span><br>';

            // Obter o nome do banco de dados selecionado
            $dbResult = $var->query('SELECT DATABASE()');
            $dbName = '';
            if ($dbResult) {
                $row = $dbResult->fetch_row();
                $dbName = $row[0];
                $dbResult->free();
            } else {
                $dbName = 'Não selecionado';
            }

            echo $indentation . '&nbsp;&nbsp;&nbsp;&nbsp;<span class="dd-key">Banco de dados selecionado</span>: <span class="dd-value">' . htmlspecialchars($dbName, ENT_QUOTES, 'UTF-8') . '</span><br>';

            // Adicione outras propriedades conforme necessário
            return;
        }

        switch ($type) {
            case 'array':
                $count = count($var);
                if ($showType) {
                    echo $indentation . '<span class="dd-type">Array</span> (' . $count . ') ';
                }
                echo '<span class="dd-punct">[</span><br>';
                foreach ($var as $key => $value) {
                    echo $indentation . '&nbsp;&nbsp;&nbsp;&nbsp;';
                    $keyType = gettype($key);
                    echo '<span class="dd-type">' . ucfirst($keyType) . '</span> ';
                    echo '<span class="dd-key">[' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . ']</span> ';
                    echo '<span class="dd-punct">=></span> ';
                    dumpVariable($value, $indent + 1, false);
                }
                echo $indentation . '<span class="dd-punct">]</span><br>';
                break;

            case 'object':
                $className = get_class($var);
                if ($showType) {
                    echo $indentation . '<span class="dd-type">Object</span> (' . $className . ') ';
                }
                echo '<span class="dd-punct">{</span><br>';

                // Obter propriedades públicas e dinâmicas
                $objectVars = get_object_vars($var);
                foreach ($objectVars as $propName => $propValue) {
                    echo $indentation . '&nbsp;&nbsp;&nbsp;&nbsp;';
                    $propType = gettype($propValue);
                    echo '<span class="dd-type">' . ucfirst($propType) . '</span> ';
                    echo '<span class="dd-key">$' . htmlspecialchars($propName, ENT_QUOTES, 'UTF-8') . '</span> ';
                    echo '<span class="dd-punct">=></span> ';
                    dumpVariable($propValue, $indent + 1, false);
                }

                // Usar reflexão para obter propriedades protegidas e privadas
                $reflection = new ReflectionClass($var);
                $properties = $reflection->getProperties(ReflectionProperty::IS_PRIVATE | ReflectionProperty::IS_PROTECTED);
                foreach ($properties as $property) {
                    $property->setAccessible(true);
                    $propName = $property->getName();
                    $propValue = $property->getValue($var);
                    $visibility = ucfirst(implode(' ', Reflection::getModifierNames($property->getModifiers())));
                    $propType = gettype($propValue);

                    echo $indentation . '&nbsp;&nbsp;&nbsp;&nbsp;';
                    echo '<span class="dd-visibility">' . $visibility . '</span> ';
                    echo '<span class="dd-type">' . ucfirst($propType) . '</span> ';
                    echo '<span class="dd-key">$' . htmlspecialchars($propName, ENT_QUOTES, 'UTF-8') . '</span> ';
                    echo '<span class="dd-punct">=></span> ';
                    dumpVariable($propValue, $indent + 1, false);
                }

                echo $indentation . '<span class="dd-punct">}</span><br>';
                break;

            default:
                if ($showType) {
                    switch ($type) {
                        case 'string':
                            echo '<span class="dd-type">String</span> (' . strlen($var) . ') ';
                            echo '<span class="dd-punct">"</span><span class="dd-value">' . htmlspecialchars($var, ENT_QUOTES, 'UTF-8') . '</span><span class="dd-punct">"</span><br>';
                            break;
                        case 'integer':
                            echo '<span class="dd-type">Integer</span> ';
                            echo '<span class="dd-value">' . $var . '</span><br>';
                            break;
                        case 'double': // float
                            echo '<span class="dd-type">Float</span> ';
                            echo '<span class="dd-value">' . $var . '</span><br>';
                            break;
                        case 'boolean':
                            echo '<span class="dd-type">Boolean</span> ';
                            echo '<span class="dd-value">' . ($var ? 'true' : 'false') . '</span><br>';
                            break;
                        case 'NULL':
                            echo '<span class="dd-type">NULL</span><br>';
                            break;
                        default:
                            echo '<span class="dd-type">' . ucfirst($type) . '</span><br>';
                    }
                } else {
                    // Apenas exibe o valor sem o tipo
                    switch ($type) {
                        case 'string':
                            echo '<span class="dd-punct">"</span><span class="dd-value">' . htmlspecialchars($var, ENT_QUOTES, 'UTF-8') . '</span><span class="dd-punct">"</span><br>';
                            break;
                        case 'integer':
                            echo '<span class="dd-value">' . $var . '</span><br>';
                            break;
                        case 'double': // float
                            echo '<span class="dd-value">' . $var . '</span><br>';
                            break;
                        case 'boolean':
                            echo '<span class="dd-value">' . ($var ? 'true' : 'false') . '</span><br>';
                            break;
                        case 'NULL':
                            echo '<span class="dd-type">NULL</span><br>';
                            break;
                        default:
                            echo '<span class="dd-type">' . ucfirst($type) . '</span><br>';
                    }
                }
                break;
        }
    }
}


if (!function_exists('object_to_array')) {

    function object_to_array($variable)
    {
        $result = (array) $variable;

        foreach ($result as &$value) {

            if ($value instanceof stdClass) {
                $value = object_to_array($value);
            }
        }

        return $result;
    }
}
if (!function_exists('extract_number_with_decimal')) {

    function extract_number_with_decimal($input)
    {
        if (is_numeric($input)) {
            return (float)$input;
        }

        if (is_string($input)) {
            preg_match('/-?\d+(\.\d+)?/', $input, $matches);
            return isset($matches[0]) ? (float)$matches[0] : null;
        }

        return null;
    }
}
