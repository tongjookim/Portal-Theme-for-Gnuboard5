<?php if (!defined('_GNUBOARD_')) exit;
echo '<link rel="stylesheet" href="'.G5_THEME_URL.'/skin/member/portal/style.css">'.PHP_EOL;

if (file_exists(G5_CAPTCHA_PATH.'/captcha.lib.php')) {
    include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');
}

$is_recv   = ($kind === 'recv');
$nick      = get_sideview($mb['mb_id'], $mb['mb_nick'], $mb['mb_email'], $mb['mb_homepage']);
$my_nick   = get_sideview($member['mb_id'], $member['mb_nick'], $member['mb_email'], $member['mb_homepage']);
$my_avatar = get_member_profile_img($member['mb_id']);
$th_avatar = get_member_profile_img($mb['mb_id']);
$reply_action = G5_BBS_URL . '/memo_form_update.php';
$captcha_html = function_exists('captcha_html') ? captcha_html() : '';
?>
<div class="ms_wrap">

    <!-- 헤더 -->
    <div class="ms_header ms_header_view">
        <a href="<?php echo $list_link ?>" class="ms_back"><i class="fa fa-chevron-left"></i></a>
        <div class="ms_header_av"><?php echo $th_avatar ?></div>
        <h1 class="ms_header_title"><?php echo $mb['mb_nick'] ?></h1>
        <a href="<?php echo $del_link ?>" onclick="del(this.href); return false;"
           class="ms_header_del" title="삭제"><i class="fa fa-trash-o"></i></a>
        <button type="button" class="ms_header_close" onclick="window.close()">×</button>
    </div>

    <!-- 날짜 라벨 -->
    <div class="ms_date_label"><?php echo $memo['me_send_datetime'] ?></div>

    <!-- 말풍선 영역 -->
    <div class="ms_bubble_area">
    <?php if ($is_recv) { ?>
        <div class="ms_row ms_row_left">
            <div class="ms_bav"><?php echo $th_avatar ?></div>
            <div class="ms_bubble_wrap">
                <span class="ms_bubble_name"><?php echo $mb['mb_nick'] ?></span>
                <div class="ms_bubble ms_bubble_recv"><?php echo nl2br(htmlspecialchars($memo['me_memo'])) ?></div>
            </div>
        </div>
    <?php } else { ?>
        <div class="ms_row ms_row_right">
            <div class="ms_bubble_wrap">
                <div class="ms_bubble ms_bubble_send"><?php echo nl2br(htmlspecialchars($memo['me_memo'])) ?></div>
            </div>
            <div class="ms_bav"><?php echo $my_avatar ?></div>
        </div>
    <?php } ?>
    </div>

    <!-- 빠른 답장 입력창 -->
    <div class="ms_quick_reply">
        <form id="fmemoreply" action="<?php echo $reply_action ?>" method="post" autocomplete="off">
            <input type="hidden" name="me_recv_mb_id" value="<?php echo $mb['mb_id'] ?>">
            <?php if ($captcha_html) { ?>
            <div class="ms_reply_captcha"><?php echo $captcha_html ?></div>
            <?php } ?>
            <div class="ms_reply_row">
                <textarea name="me_memo" id="me_reply_memo" class="ms_reply_textarea"
                          placeholder="<?php echo htmlspecialchars($mb['mb_nick']) ?>님께 쪽지 보내기…" rows="1"></textarea>
                <button type="submit" class="ms_reply_btn">
                    <i class="fa fa-paper-plane"></i>
                </button>
            </div>
            <p class="ms_reply_msg" id="ms_reply_msg"></p>
        </form>
    </div>

    <!-- 이전/다음 -->
    <div class="ms_view_nav">
        <?php if ($prev_link) { ?>
        <a href="<?php echo $prev_link ?>" class="ms_nav_btn"><i class="fa fa-chevron-left"></i> 이전</a>
        <?php } else { ?>
        <span></span>
        <?php } ?>
        <?php if ($next_link) { ?>
        <a href="<?php echo $next_link ?>" class="ms_nav_btn">다음 <i class="fa fa-chevron-right"></i></a>
        <?php } else { ?>
        <span></span>
        <?php } ?>
    </div>

    <!-- 푸터 -->
    <div class="ms_footer">
        <button type="button" class="ms_close_btn" onclick="window.close()">닫기</button>
    </div>

</div>

<script>
(function() {
    var form    = document.getElementById('fmemoreply');
    var textarea = document.getElementById('me_reply_memo');
    var msg     = document.getElementById('ms_reply_msg');
    var btn     = form.querySelector('.ms_reply_btn');

    /* textarea 자동 높이 */
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    /* Shift+Enter: 줄바꿈 / Enter: 전송 */
    textarea.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            form.dispatchEvent(new Event('submit', {cancelable: true}));
        }
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        <?php if (function_exists('chk_captcha_js')) { echo chk_captcha_js(); } ?>

        var text = textarea.value.trim();
        if (!text) { textarea.focus(); return; }

        btn.disabled = true;
        msg.className = 'ms_reply_msg';
        msg.textContent = '';

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form)
        })
        .then(function(r) { return r.text(); })
        .then(function(html) {
            if (html.indexOf('전달하였습니다') !== -1) {
                textarea.value = '';
                textarea.style.height = '';
                showMsg('쪽지를 보냈습니다.', 'ok');
            } else {
                showMsg('전송에 실패했습니다. 다시 시도해주세요.', 'err');
            }
            btn.disabled = false;
        })
        .catch(function() {
            showMsg('네트워크 오류가 발생했습니다.', 'err');
            btn.disabled = false;
        });
    });

    function showMsg(text, type) {
        msg.textContent = text;
        msg.className = 'ms_reply_msg ms_reply_msg_' + type;
        clearTimeout(msg._t);
        msg._t = setTimeout(function() { msg.textContent = ''; msg.className = 'ms_reply_msg'; }, 3000);
    }
})();
</script>
