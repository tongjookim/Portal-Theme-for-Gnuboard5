<?php if (!defined('_GNUBOARD_')) exit;
echo '<link rel="stylesheet" href="'.G5_THEME_URL.'/skin/member/portal/style.css">'.PHP_EOL;
?>
<div class="ms_wrap">

    <!-- 헤더 -->
    <div class="ms_header">
        <a href="./memo.php?kind=recv" class="ms_back"><i class="fa fa-chevron-left"></i></a>
        <h1 class="ms_header_title"><i class="fa fa-pencil-square-o"></i> 쪽지 보내기</h1>
        <button type="button" class="ms_header_close" onclick="window.close()">×</button>
    </div>

    <!-- 폼 -->
    <form name="fmemoform" action="<?php echo $memo_action_url ?>"
          onsubmit="return fmemoform_submit(this);" method="post" autocomplete="off"
          class="ms_form">

        <!-- 받는 사람 -->
        <div class="ms_form_row">
            <label class="ms_form_label" for="me_recv_mb_id">
                <i class="fa fa-user-o"></i> 받는 사람
            </label>
            <input type="text" name="me_recv_mb_id" id="me_recv_mb_id" required
                   value="<?php echo $me_recv_mb_id ?>"
                   class="ms_form_input" placeholder="회원 아이디 (콤마로 여러 명)">
            <?php if ($config['cf_memo_send_point']) { ?>
            <span class="ms_form_tip">1인당 <?php echo number_format($config['cf_memo_send_point']) ?>P 차감</span>
            <?php } ?>
        </div>

        <!-- 내용 -->
        <div class="ms_form_row ms_form_row_content">
            <label class="ms_form_label" for="me_memo">
                <i class="fa fa-comment-o"></i> 내용
            </label>
            <textarea name="me_memo" id="me_memo" required
                      class="ms_form_textarea" placeholder="쪽지 내용을 입력하세요."><?php echo $content ?></textarea>
        </div>

        <!-- 캡차 -->
        <?php $captcha = captcha_html(); if ($captcha) { ?>
        <div class="ms_form_row ms_form_captcha">
            <?php echo $captcha ?>
        </div>
        <?php } ?>

        <!-- 버튼 -->
        <div class="ms_footer ms_form_btns">
            <button type="submit" id="btn_submit" class="ms_send_btn">
                <i class="fa fa-paper-plane"></i> 보내기
            </button>
            <button type="button" class="ms_close_btn" onclick="window.close()">닫기</button>
        </div>

    </form>
</div>

<script>
function fmemoform_submit(f) {
    <?php echo chk_captcha_js() ?>
    return true;
}
</script>
