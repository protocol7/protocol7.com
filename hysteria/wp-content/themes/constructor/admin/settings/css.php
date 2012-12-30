<?php __('CSS', 'constructor'); // requeried for correct translation 
$css_file = CONSTRUCTOR_DIRECTORY .'/themes/'.$admin['theme'].'/style.css';
?>
<table class="form-table">
<?php if (!is_writable($css_file)) : ?>
    <tr>
        <th scope="row" valign="top" class="th-full updated"><?php printf(__('<font color="red"><b>Warning!</b></font>: File "%s" is not writable.', 'constructor'), $css_file); ?></th>
    </tr>
    <tr>
        <td class="td-full"><textarea name="null[css]" class="big" readonly="readonly"><?php echo file_get_contents($css_file)?></textarea></td>
    </tr>
<?php else: ?>
    <tr>
        <td class="td-full"><textarea name="constructor[css]" class="big"><?php echo file_get_contents($css_file)?></textarea></td>
    </tr>
<?php endif; ?>
</table>