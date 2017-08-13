<?php
require __DIR__. '/../tests/bootstrap.php';

use Upyun\Config;
use Upyun\Upyun;

$config = new Config(BUCKET, USER_NAME, PWD);
//$config = new Config('jinlaisandbox-images', 'jinlaisandbox', 'jinlaisandbox');
$upyun = new Upyun($config);

$start = null;
$total = 0;

$directory_name = isset( $_REQUEST['dir'] )? '/'.$_REQUEST['dir'].'/': '/';
do {
    $list = $upyun->read(
		$directory_name,
		null,
		array(
        'X-List-Limit' => 100,
        'X-List-Iter' => $start,
		)
	);

    if ( is_array($list['files']) )
	{
		echo '<table>
				<thead>
					<tr>
						<th>文件名</th><th>文件大小</th><th>修改时间</th>
					</tr>
				</thead>
				<tbody>';
        foreach ($list['files'] as $file)
		{
			echo '<tr>';

            $total++;
            if ($file['type'] === 'N')
			{
                echo '<td>'.$file['name']. '</td>';
            }
			else
			{
                echo '<td><a href="./list-all-file.php?dir='. $directory_name. $file['name']. '">'.$file['name'].'</a></td>';
            }

            echo '	<td>'. round($file['size'] / 1024, 2). 'KB</td>';
            echo '	<td>'. date('Y-m-d H:i:s', $file['time']). '</td>';

            echo '</tr>';
        }
		echo '</tbody>
			</table>';
    }
    $start = $list['iter'];
} while (!$list['is_end']);

echo '总共存有文件 ' . $total . ' 个';
