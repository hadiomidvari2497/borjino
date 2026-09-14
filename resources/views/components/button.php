<?php
/**
 * Button Component
 * Usage: include 'components/button.php' with helper functions
 */

function btn($label, $href = null, $class = 'btn btn-primary', $attrs = [], $icon = '') {
    $attrStr = '';
    foreach ($attrs as $k => $v) {
        $attrStr .= ' '.htmlspecialchars($k, ENT_QUOTES, 'UTF-8').'="'.htmlspecialchars($v, ENT_QUOTES, 'UTF-8').'"';
    }
    if ($href) {
        return '<a href="'.htmlspecialchars($href, ENT_QUOTES, 'UTF-8').'" class="'.htmlspecialchars($class, ENT_QUOTES, 'UTF-8').'"'.$attrStr.'>'.($icon ? $icon.' ' : '').htmlspecialchars($label, ENT_QUOTES, 'UTF-8').'</a>';
    }
    return '<button type="button" class="'.htmlspecialchars($class, ENT_QUOTES, 'UTF-8').'"'.$attrStr.'>'.($icon ? $icon.' ' : '').htmlspecialchars($label, ENT_QUOTES, 'UTF-8').'</button>';
}

function btnSubmit($label = 'ذخیره', $class = 'btn btn-primary', $attrs = [], $icon = '') {
    $attrStr = '';
    foreach ($attrs as $k => $v) {
        $attrStr .= ' '.htmlspecialchars($k, ENT_QUOTES, 'UTF-8').'="'.htmlspecialchars($v, ENT_QUOTES, 'UTF-8').'"';
    }
    return '<button type="submit" class="'.htmlspecialchars($class, ENT_QUOTES, 'UTF-8').'"'.$attrStr.'>'.($icon ? $icon.' ' : '').htmlspecialchars($label, ENT_QUOTES, 'UTF-8').'</button>';
}

function btnLink($label, $href, $class = 'btn btn-secondary', $attrs = [], $icon = '') {
    $attrStr = '';
    foreach ($attrs as $k => $v) {
        $attrStr .= ' '.htmlspecialchars($k, ENT_QUOTES, 'UTF-8').'="'.htmlspecialchars($v, ENT_QUOTES, 'UTF-8').'"';
    }
    return '<a href="'.htmlspecialchars($href, ENT_QUOTES, 'UTF-8').'" class="'.htmlspecialchars($class, ENT_QUOTES, 'UTF-8').'"'.$attrStr.'>'.($icon ? $icon.' ' : '').htmlspecialchars($label, ENT_QUOTES, 'UTF-8').'</a>';
}

function btnDanger($label, $href = null, $class = 'btn btn-danger', $attrs = [], $icon = '', $confirm = '') {
    $attrStr = '';
    foreach ($attrs as $k => $v) {
        $attrStr .= ' '.htmlspecialchars($k, ENT_QUOTES, 'UTF-8').'="'.htmlspecialchars($v, ENT_QUOTES, 'UTF-8').'"';
    }
    if ($confirm) {
        $attrStr .= ' data-confirm="'.htmlspecialchars($confirm, ENT_QUOTES, 'UTF-8').'"';
    }
    if ($href) {
        return '<a href="'.htmlspecialchars($href, ENT_QUOTES, 'UTF-8').'" class="'.htmlspecialchars($class, ENT_QUOTES, 'UTF-8').'"'.$attrStr.'>'.($icon ? $icon.' ' : '').htmlspecialchars($label, ENT_QUOTES, 'UTF-8').'</a>';
    }
    return '<button type="button" class="'.htmlspecialchars($class, ENT_QUOTES, 'UTF-8').'"'.$attrStr.'>'.($icon ? $icon.' ' : '').htmlspecialchars($label, ENT_QUOTES, 'UTF-8').'</button>';
}

function btnOutline($label, $href = null, $variant = 'primary', $class = '', $attrs = [], $icon = '') {
    $btnClass = 'btn btn-outline-'.$variant.' '.trim($class);
    return btn($label, $href, $btnClass, $attrs, $icon);
}

function btnIcon($icon, $class = 'btn btn-icon btn-secondary', $attrs = [], $label = '') {
    $attrStr = '';
    foreach ($attrs as $k => $v) {
        $attrStr .= ' '.htmlspecialchars($k, ENT_QUOTES, 'UTF-8').'="'.htmlspecialchars($v, ENT_QUOTES, 'UTF-8').'"';
    }
    $ariaLabel = $label ? ' aria-label="'.htmlspecialchars($label, ENT_QUOTES, 'UTF-8').'"' : '';
    return '<button type="button" class="'.htmlspecialchars($class, ENT_QUOTES, 'UTF-8').'"'.$attrStr.$ariaLabel.'>'.$icon.'</button>';
}

function btnIconLink($icon, $href, $class = 'btn btn-icon btn-secondary', $attrs = [], $label = '') {
    $attrStr = '';
    foreach ($attrs as $k => $v) {
        $attrStr .= ' '.htmlspecialchars($k, ENT_QUOTES, 'UTF-8').'="'.htmlspecialchars($v, ENT_QUOTES, 'UTF-8').'"';
    }
    $ariaLabel = $label ? ' aria-label="'.htmlspecialchars($label, ENT_QUOTES, 'UTF-8').'"' : '';
    return '<a href="'.htmlspecialchars($href, ENT_QUOTES, 'UTF-8').'" class="'.htmlspecialchars($class, ENT_QUOTES, 'UTF-8').'"'.$attrStr.$ariaLabel.'>'.$icon.'</a>';
}

function btnGroup($buttons) {
    $output = '<div class="btn-group" style="display: inline-flex; gap: 0.25rem;">';
    foreach ($buttons as $btn) {
        $output .= btn($btn['label'], $btn['href'] ?? null, $btn['class'] ?? 'btn btn-secondary', $btn['attrs'] ?? [], $btn['icon'] ?? '');
    }
    $output .= '</div>';
    return $output;
}
?>