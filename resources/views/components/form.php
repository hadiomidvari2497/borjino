<?php
/**
 * Form Components
 * Usage: include 'components/form.php' with helper functions
 */

function formField($label, $input, $help = '', $error = '', $required = false) {
    $output = '<div class="form-group">';
    $output .= '<label class="form-label">';
    $output .= htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
    if ($required) {
        $output .= ' <span class="text-danger" aria-hidden="true">*</span>';
    }
    $output .= '</label>';
    $output .= $input;
    if ($help) {
        $output .= '<div class="form-text">'.htmlspecialchars($help, ENT_QUOTES, 'UTF-8').'</div>';
    }
    if ($error) {
        $output .= '<div class="form-text" style="color: var(--danger);">'.htmlspecialchars($error, ENT_QUOTES, 'UTF-8').'</div>';
    }
    $output .= '</div>';
    return $output;
}

function formInput($name, $type = 'text', $value = '', $attrs = [], $errors = []) {
    $attrStr = '';
    foreach ($attrs as $k => $v) {
        $attrStr .= ' '.htmlspecialchars($k, ENT_QUOTES, 'UTF-8').'="'.htmlspecialchars($v, ENT_QUOTES, 'UTF-8').'"';
    }
    $error = $errors[$name] ?? '';
    $class = 'form-control'.($error ? ' is-invalid' : '');
    return '<input type="'.htmlspecialchars($type, ENT_QUOTES, 'UTF-8').'" name="'.htmlspecialchars($name, ENT_QUOTES, 'UTF-8').'" value="'.htmlspecialchars($value, ENT_QUOTES, 'UTF-8').'" class="'.$class.'"'.$attrStr.'>';
}

function formSelect($name, $options, $value = '', $attrs = [], $errors = [], $placeholder = 'انتخاب کنید') {
    $attrStr = '';
    foreach ($attrs as $k => $v) {
        $attrStr .= ' '.htmlspecialchars($k, ENT_QUOTES, 'UTF-8').'="'.htmlspecialchars($v, ENT_QUOTES, 'UTF-8').'"';
    }
    $error = $errors[$name] ?? '';
    $class = 'form-control form-select'.($error ? ' is-invalid' : '');
    $output = '<select name="'.htmlspecialchars($name, ENT_QUOTES, 'UTF-8').'" class="'.$class.'"'.$attrStr.'>';
    $output .= '<option value="">'.htmlspecialchars($placeholder, ENT_QUOTES, 'UTF-8').'</option>';
    foreach ($options as $optValue => $optLabel) {
        $selected = $value == $optValue ? ' selected' : '';
        $output .= '<option value="'.htmlspecialchars($optValue, ENT_QUOTES, 'UTF-8').'"'.$selected.'>'.htmlspecialchars($optLabel, ENT_QUOTES, 'UTF-8').'</option>';
    }
    $output .= '</select>';
    return $output;
}

function formTextarea($name, $value = '', $attrs = [], $errors = []) {
    $attrStr = '';
    foreach ($attrs as $k => $v) {
        $attrStr .= ' '.htmlspecialchars($k, ENT_QUOTES, 'UTF-8').'="'.htmlspecialchars($v, ENT_QUOTES, 'UTF-8').'"';
    }
    $error = $errors[$name] ?? '';
    $class = 'form-control'.($error ? ' is-invalid' : '');
    return '<textarea name="'.htmlspecialchars($name, ENT_QUOTES, 'UTF-8').'" class="'.$class.'"'.$attrStr.'>'.htmlspecialchars($value, ENT_QUOTES, 'UTF-8').'</textarea>';
}

function formCheckbox($name, $label, $value = false, $attrs = [], $errors = []) {
    $attrStr = '';
    foreach ($attrs as $k => $v) {
        $attrStr .= ' '.htmlspecialchars($k, ENT_QUOTES, 'UTF-8').'="'.htmlspecialchars($v, ENT_QUOTES, 'UTF-8').'"';
    }
    $checked = $value ? ' checked' : '';
    $output = '<div class="form-check">';
    $output .= '<input type="checkbox" name="'.htmlspecialchars($name, ENT_QUOTES, 'UTF-8').'" class="form-check-input" value="1"'.$checked.$attrStr.'>';
    $output .= '<label class="form-check-label">'.htmlspecialchars($label, ENT_QUOTES, 'UTF-8').'</label>';
    $output .= '</div>';
    return $output;
}

function formRadioGroup($name, $options, $value = '', $attrs = [], $errors = []) {
    $output = '<div class="form-check">';
    foreach ($options as $optValue => $optLabel) {
        $checked = $value == $optValue ? ' checked' : '';
        $id = $name.'_'.htmlspecialchars($optValue, ENT_QUOTES, 'UTF-8');
        $output .= '<div style="margin-bottom: 0.5rem;">';
        $output .= '<input type="radio" name="'.htmlspecialchars($name, ENT_QUOTES, 'UTF-8').'" id="'.htmlspecialchars($id, ENT_QUOTES, 'UTF-8').'" class="form-check-input" value="'.htmlspecialchars($optValue, ENT_QUOTES, 'UTF-8').'"'.$checked.'>';
        $output .= '<label class="form-check-label" for="'.htmlspecialchars($id, ENT_QUOTES, 'UTF-8').'">'.htmlspecialchars($optLabel, ENT_QUOTES, 'UTF-8').'</label>';
        $output .= '</div>';
    }
    $output .= '</div>';
    return $output;
}

function formHidden($name, $value) {
    return '<input type="hidden" name="'.htmlspecialchars($name, ENT_QUOTES, 'UTF-8').'" value="'.htmlspecialchars($value, ENT_QUOTES, 'UTF-8').'">';
}

function formSubmit($label = 'ذخیره', $attrs = []) {
    $attrStr = '';
    foreach ($attrs as $k => $v) {
        $attrStr .= ' '.htmlspecialchars($k, ENT_QUOTES, 'UTF-8').'="'.htmlspecialchars($v, ENT_QUOTES, 'UTF-8').'"';
    }
    return '<button type="submit" class="btn btn-primary"'.$attrStr.'>'.htmlspecialchars($label, ENT_QUOTES, 'UTF-8').'</button>';
}

function formButton($label, $type = 'button', $class = 'btn btn-secondary', $attrs = []) {
    $attrStr = '';
    foreach ($attrs as $k => $v) {
        $attrStr .= ' '.htmlspecialchars($k, ENT_QUOTES, 'UTF-8').'="'.htmlspecialchars($v, ENT_QUOTES, 'UTF-8').'"';
    }
    return '<button type="'.htmlspecialchars($type, ENT_QUOTES, 'UTF-8').'" class="'.htmlspecialchars($class, ENT_QUOTES, 'UTF-8').'"'.$attrStr.'>'.htmlspecialchars($label, ENT_QUOTES, 'UTF-8').'</button>';
}

function formActionButtons($buttons) {
    $output = '<div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">';
    foreach ($buttons as $btn) {
        $type = $btn['type'] ?? 'button';
        $class = $btn['class'] ?? 'btn btn-secondary';
        $label = $btn['label'] ?? '';
        $href = $btn['href'] ?? null;
        $attrs = $btn['attrs'] ?? [];
        $attrStr = '';
        foreach ($attrs as $k => $v) {
            $attrStr .= ' '.htmlspecialchars($k, ENT_QUOTES, 'UTF-8').'="'.htmlspecialchars($v, ENT_QUOTES, 'UTF-8').'"';
        }
        if ($href) {
            $output .= '<a href="'.htmlspecialchars($href, ENT_QUOTES, 'UTF-8').'" class="'.htmlspecialchars($class, ENT_QUOTES, 'UTF-8').'"'.$attrStr.'>'.htmlspecialchars($label, ENT_QUOTES, 'UTF-8').'</a>';
        } else {
            $output .= '<button type="'.htmlspecialchars($type, ENT_QUOTES, 'UTF-8').'" class="'.htmlspecialchars($class, ENT_QUOTES, 'UTF-8').'"'.$attrStr.'>'.htmlspecialchars($label, ENT_QUOTES, 'UTF-8').'</button>';
        }
    }
    $output .= '</div>';
    return $output;
}
?>