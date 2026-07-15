<?php
if (!defined('_GNUBOARD_')) exit;
echo '<link rel="stylesheet" href="'.$search_skin_url.'/style.css">'.PHP_EOL;
?>
<div id="mptl_sch_wrap">

<!-- 검색 폼 -->
<div class="mptl_sch_form_area">
    <form name="fsearch" onsubmit="return mptl_fsearch_submit(this);" method="get">
    <input type="hidden" name="srows" value="<?php echo $srows ?>">
    <div class="mptl_sch_row">
        <div class="mptl_sch_select_wrap">
            <select name="sfl" class="mptl_sch_select">
                <option value="wr_subject||wr_content"<?php echo get_selected($sfl, "wr_subject||wr_content") ?>>제목+내용</option>
                <option value="wr_subject"<?php echo get_selected($sfl, "wr_subject") ?>>제목</option>
                <option value="wr_content"<?php echo get_selected($sfl, "wr_content") ?>>내용</option>
                <option value="mb_id"<?php echo get_selected($sfl, "mb_id") ?>>아이디</option>
                <option value="wr_name"<?php echo get_selected($sfl, "wr_name") ?>>이름</option>
            </select>
        </div>
        <input type="text" name="stx" id="stx" value="<?php echo $text_stx ?>" class="mptl_sch_input" required placeholder="검색어 입력" maxlength="20">
        <button type="submit" class="mptl_sch_btn"><i class="fa fa-search"></i></button>
    </div>
    <div class="mptl_sch_op">
        <label class="mptl_sch_radio"><input type="radio" name="sop" value="and"<?php echo ($sop == 'and') ? ' checked' : '' ?>> AND</label>
        <label class="mptl_sch_radio"><input type="radio" name="sop" value="or"<?php echo ($sop == 'or')  ? ' checked' : '' ?>> OR</label>
    </div>
    <?php echo $group_select ?>
    <script>document.getElementById("gr_id").value = "<?php echo $gr_id ?>";</script>
    </form>
</div>

<div id="mptl_sch_result">
<?php if ($stx): ?>

    <?php if ($board_count): ?>
    <!-- 결과 요약 -->
    <div class="mptl_sch_summary">
        <strong class="mptl_sch_stx">"<?php echo htmlspecialchars($stx, ENT_QUOTES) ?>"</strong>
        <span class="mptl_sch_counts">게시판 <em><?php echo $board_count ?></em>개 · 게시물 <em><?php echo number_format($total_count) ?></em>건</span>
    </div>

    <!-- 게시판 필터 탭 -->
    <nav class="mptl_sch_board_nav">
        <ul>
            <li><a href="?<?php echo $search_query ?>&amp;gr_id=<?php echo $gr_id ?>" <?php echo $sch_all ?>>전체</a></li>
            <?php echo $str_board_list ?>
        </ul>
    </nav>
    <?php else: ?>
    <div class="mptl_sch_empty">
        <i class="fa fa-search"></i>
        <p>"<?php echo htmlspecialchars($stx, ENT_QUOTES) ?>"에 대한 검색 결과가 없습니다.</p>
    </div>
    <?php endif; ?>

    <!-- 결과 목록 -->
    <?php if ($board_count):
        $k = 0;
        for ($idx = $table_index; $idx < count($search_table) && $k < $rows; $idx++):
    ?>
    <section class="mptl_sch_section">
        <div class="mptl_sch_section_head">
            <h2 class="mptl_sch_section_title">
                <a href="<?php echo get_pretty_url($search_table[$idx], '', $search_query) ?>"><?php echo $bo_subject[$idx] ?></a>
            </h2>
            <a href="<?php echo get_pretty_url($search_table[$idx], '', $search_query) ?>" class="mptl_sch_more">더보기 ›</a>
        </div>
        <ul class="mptl_sch_list">
        <?php for ($i = 0; $i < count($list[$idx]) && $k < $rows; $i++, $k++):
            $is_cmt    = $list[$idx][$i]['wr_is_comment'];
            $cmt_icon  = $is_cmt ? '<i class="fa fa-commenting-o mptl_sch_cmt_icon"></i> ' : '';
            $href_anch = $is_cmt ? '#c_'.$list[$idx][$i]['wr_id'] : '';
        ?>
        <li class="mptl_sch_item">
            <a href="<?php echo $list[$idx][$i]['href'].$href_anch ?>" class="mptl_sch_title">
                <?php echo $cmt_icon ?><?php echo $list[$idx][$i]['subject'] ?>
            </a>
            <?php if ($list[$idx][$i]['content']): ?>
            <p class="mptl_sch_excerpt"><?php echo $list[$idx][$i]['content'] ?></p>
            <?php endif; ?>
            <div class="mptl_sch_meta">
                <span><?php echo $list[$idx][$i]['name'] ?></span>
                <span class="mptl_sch_sep">·</span>
                <span><?php echo $list[$idx][$i]['wr_datetime'] ?></span>
            </div>
        </li>
        <?php endfor; ?>
        </ul>
    </section>
    <?php endfor; endif; ?>

<?php endif; /* $stx */ ?>
</div>

<div class="mptl_sch_pager"><?php echo $write_pages ?></div>

</div><!-- /#mptl_sch_wrap -->

<script>
function mptl_fsearch_submit(f) {
    var stx = f.stx.value.trim();
    if (stx.length < 2) { alert('검색어는 두 글자 이상 입력하세요.'); f.stx.select(); f.stx.focus(); return false; }
    var spaces = 0;
    for (var i = 0; i < stx.length; i++) { if (stx[i] === ' ') spaces++; }
    if (spaces > 1) { alert('공백은 한 개까지만 허용됩니다.'); f.stx.select(); f.stx.focus(); return false; }
    f.stx.value = stx;
    return true;
}
</script>
