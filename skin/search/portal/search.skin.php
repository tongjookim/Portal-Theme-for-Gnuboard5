<?php
if (!defined('_GNUBOARD_')) exit;
echo '<link rel="stylesheet" href="'.$search_skin_url.'/style.css">'.PHP_EOL;
?>
<div id="ptl_sch_wrap">

<!-- 검색 폼 -->
<div class="ptl_sch_form_area">
    <form name="fsearch" onsubmit="return ptl_fsearch_submit(this);" method="get">
    <input type="hidden" name="srows" value="<?php echo $srows ?>">
    <div class="ptl_sch_form_inner">
        <?php echo $group_select ?>
        <script>document.getElementById("gr_id").value = "<?php echo $gr_id ?>";</script>
        <div class="ptl_sch_row">
            <div class="ptl_sch_select_wrap">
                <select name="sfl" class="ptl_sch_select">
                    <option value="wr_subject||wr_content"<?php echo get_selected($sfl, "wr_subject||wr_content") ?>>제목+내용</option>
                    <option value="wr_subject"<?php echo get_selected($sfl, "wr_subject") ?>>제목</option>
                    <option value="wr_content"<?php echo get_selected($sfl, "wr_content") ?>>내용</option>
                    <option value="mb_id"<?php echo get_selected($sfl, "mb_id") ?>>회원아이디</option>
                    <option value="wr_name"<?php echo get_selected($sfl, "wr_name") ?>>이름</option>
                </select>
            </div>
            <input type="text" name="stx" id="stx" value="<?php echo $text_stx ?>" class="ptl_sch_input" required placeholder="검색어 입력 (2자 이상)">
            <button type="submit" class="ptl_sch_btn"><i class="fa fa-search"></i> 검색</button>
        </div>
        <div class="ptl_sch_op">
            <label class="ptl_sch_radio"><input type="radio" name="sop" value="and"<?php echo ($sop == 'and') ? ' checked' : '' ?>> AND</label>
            <label class="ptl_sch_radio"><input type="radio" name="sop" value="or"<?php echo ($sop == 'or')  ? ' checked' : '' ?>> OR</label>
        </div>
    </div>
    </form>
</div>

<div id="ptl_sch_result">
<?php if ($stx): ?>

    <!-- 결과 요약 -->
    <?php if ($board_count): ?>
    <div class="ptl_sch_summary">
        <strong class="ptl_sch_stx">"<?php echo htmlspecialchars($stx, ENT_QUOTES) ?>"</strong> 검색 결과
        <span class="ptl_sch_counts">
            게시판 <em><?php echo $board_count ?></em>개 &middot;
            게시물 <em><?php echo number_format($total_count) ?></em>건
            <span class="ptl_sch_page">(<?php echo number_format($page) ?>/<?php echo number_format($total_page) ?> 페이지)</span>
        </span>
    </div>

    <!-- 게시판 필터 탭 -->
    <nav class="ptl_sch_board_nav">
        <ul>
            <li><a href="?<?php echo $search_query ?>&amp;gr_id=<?php echo $gr_id ?>" <?php echo $sch_all ?>>전체</a></li>
            <?php echo $str_board_list ?>
        </ul>
    </nav>
    <?php else: ?>
    <div class="ptl_sch_empty">
        <i class="fa fa-search"></i>
        <p><strong>"<?php echo htmlspecialchars($stx, ENT_QUOTES) ?>"</strong>에 대한 검색 결과가 없습니다.</p>
        <p class="ptl_sch_empty_tip">다른 검색어를 사용하거나 검색 범위를 변경해 보세요.</p>
    </div>
    <?php endif; ?>

    <!-- 게시물 결과 목록 -->
    <?php if ($board_count): ?>
    <div class="ptl_sch_results">
    <?php
    $k = 0;
    for ($idx = $table_index; $idx < count($search_table) && $k < $rows; $idx++):
    ?>
        <section class="ptl_sch_board_section">
            <div class="ptl_sch_board_head">
                <h2 class="ptl_sch_board_title">
                    <i class="fa fa-list-alt"></i>
                    <a href="<?php echo get_pretty_url($search_table[$idx], '', $search_query) ?>"><?php echo $bo_subject[$idx] ?></a>
                </h2>
                <a href="<?php echo get_pretty_url($search_table[$idx], '', $search_query) ?>" class="ptl_sch_board_more">게시판 더보기 ›</a>
            </div>
            <ul class="ptl_sch_list">
            <?php for ($i = 0; $i < count($list[$idx]) && $k < $rows; $i++, $k++):
                $is_cmt = $list[$idx][$i]['wr_is_comment'];
                $cmt_prefix = $is_cmt ? '<i class="fa fa-commenting-o ptl_sch_cmt_icon"></i> ' : '';
                $href_anchor = $is_cmt ? '#c_'.$list[$idx][$i]['wr_id'] : '';
            ?>
            <li class="ptl_sch_item">
                <div class="ptl_sch_item_head">
                    <a href="<?php echo $list[$idx][$i]['href'].$href_anchor ?>" class="ptl_sch_title">
                        <?php echo $cmt_prefix ?><?php echo $list[$idx][$i]['subject'] ?>
                    </a>
                    <a href="<?php echo $list[$idx][$i]['href'].$href_anchor ?>" target="_blank" class="ptl_sch_new_win" title="새창으로 보기"><i class="fa fa-external-link"></i></a>
                </div>
                <?php if ($list[$idx][$i]['content']): ?>
                <p class="ptl_sch_excerpt"><?php echo $list[$idx][$i]['content'] ?></p>
                <?php endif; ?>
                <div class="ptl_sch_meta">
                    <span class="ptl_sch_author"><?php echo $list[$idx][$i]['name'] ?></span>
                    <span class="ptl_sch_sep">·</span>
                    <span class="ptl_sch_date"><i class="fa fa-clock-o"></i> <?php echo $list[$idx][$i]['wr_datetime'] ?></span>
                </div>
            </li>
            <?php endfor; ?>
            </ul>
        </section>
    <?php endfor; ?>
    </div>
    <?php endif; ?>

<?php endif; /* $stx */ ?>
</div><!-- /#ptl_sch_result -->

<!-- 페이지네이션 -->
<div class="ptl_sch_pager"><?php echo $write_pages ?></div>

</div><!-- /#ptl_sch_wrap -->

<script>
function ptl_fsearch_submit(f) {
    var stx = f.stx.value.trim();
    if (stx.length < 2) {
        alert('검색어는 두 글자 이상 입력하세요.');
        f.stx.select(); f.stx.focus();
        return false;
    }
    var spaces = 0;
    for (var i = 0; i < stx.length; i++) { if (stx[i] === ' ') spaces++; }
    if (spaces > 1) {
        alert('검색어에 공백은 한 개까지만 허용됩니다.');
        f.stx.select(); f.stx.focus();
        return false;
    }
    f.stx.value = stx;
    return true;
}
</script>
