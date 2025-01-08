<?php
  if(isset($_GET['page'])){
    $page = $_GET['page'];
  }else{
    $page = 1;
  }
  $record = 1;
  $offset = ($page - 1)* $record;
  $pageTotal = 3;
?>

<nav aria-label="Page navigation example">
    <ul class="pagination">
        <li class="page-item <?=$page > 1 ? '': 'disabled'?>"><a class="page-link" href="?page=<?=$page-1?>">Previous</a></li>
            <?php for ($i=1; $i <= $pageTotal; $i++) :?>
        <li class="page-item <?=$page==$i?'active':''?>"><a class="page-link" href="?page=<?=$i?>"><?=$i?></a></li>
            <?php endfor;?>
        <li class="page-item <?=$page < $pageTotal ? '': 'disabled'?>" ><a class="page-link" href="?page=<?=$page+1?>">Next</a></li>
    </ul>
</nav>