<?php if (!defined('_GNUBOARD_')) exit;
echo '<link rel="stylesheet" href="'.G5_THEME_URL.'/skin/member/portal/style.css">'.PHP_EOL;
?>
<div class="ms_wrap">

    <!-- 헤더 -->
    <div class="ms_header">
        <h1 class="ms_header_title"><i class="fa fa-comment-o"></i> 내 쪽지함</h1>
        <span class="ms_header_count"><?php echo number_format($total_count) ?>통</span>
        <button type="button" class="ms_header_close" onclick="window.close()">×</button>
    </div>

    <!-- 탭 -->
    <div class="ms_tabs">
        <a href="./memo.php?kind=recv" class="ms_tab<?php echo $kind=='recv'?' ms_tab_on':'' ?>">
            <i class="fa fa-inbox"></i> 받은쪽지
        </a>
        <a href="./memo.php?kind=send" class="ms_tab<?php echo $kind=='send'?' ms_tab_on':'' ?>">
            <i class="fa fa-paper-plane-o"></i> 보낸쪽지
        </a>
        <a href="./memo_form.php" class="ms_tab_compose">
            <i class="fa fa-pencil-square-o"></i> 쓰기
        </a>
    </div>

    <!-- 목록 -->
    <ul class="ms_list">
    <?php foreach ((array)$list as $row) {
        $is_unread = ($kind === 'recv' && substr($row['me_read_datetime'], 0, 1) == 0);
        $preview   = utf8_strcut(strip_tags($row['me_memo']), 40, '…');
        $avatar    = get_member_profile_img($row['mb_id']);
    ?>
    <li class="ms_item<?php echo $is_unread ? ' ms_unread' : '' ?>">
        <!-- 카드 전체 클릭 오버레이 (앵커 중첩 방지) -->
        <a href="<?php echo $row['view_href'] ?>" class="ms_item_link" aria-label="쪽지 보기"></a>
        <div class="ms_avatar"><?php echo $avatar ?></div>
        <div class="ms_item_body">
            <div class="ms_item_top">
                <span class="ms_item_name"><?php echo $row['name'] ?></span>
                <?php if ($is_unread) { ?><span class="ms_badge">N</span><?php } ?>
                <span class="ms_item_time"><?php echo $row['send_datetime'] ?></span>
            </div>
            <div class="ms_item_preview"><?php echo $preview ?></div>
        </div>
        <a href="<?php echo $row['del_href'] ?>" onclick="del(this.href); return false;"
           class="ms_del" title="삭제"><i class="fa fa-trash-o"></i></a>
    </li>
    <?php } ?>
    <?php if (empty($list)) { ?>
    <li class="ms_empty"><i class="fa fa-inbox"></i><br>쪽지가 없습니다.</li>
    <?php } ?>
    </ul>

    <!-- 페이징 + 닫기 -->
    <div class="ms_footer">
        <?php echo $write_pages ?>
        <p class="ms_keep_info"><i class="fa fa-clock-o"></i> 보관 최장 <?php echo $config['cf_memo_del'] ?>일</p>
        <button type="button" class="ms_close_btn" onclick="window.close()">닫기</button>
    </div>

</div>
