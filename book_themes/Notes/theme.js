function get_theme(path)
{
	return (
	
		[
			['<IMG ALIGN=TOP SRC=' + path +'/folder.gif>', '<IMG ALIGN=TOP SRC=' + path +'/folderopen.gif>'],
			['<IMG ALIGN=TOP SRC=' + path +'/plus.gif>', '<IMG ALIGN=TOP SRC=' + path +'/minus.gif>'],
			['<IMG ALIGN=TOP SRC=' + path +'/plusbottom.gif>', '<IMG ALIGN=TOP SRC=' + path +'/minusbottom.gif>'],
			'<IMG ALIGN=TOP SRC=' + path +'/page.gif>',
			['<IMG ALIGN=TOP SRC=' + path +'/join.gif>', '<IMG ALIGN=TOP SRC=' + path +'/joinbottom.gif>'],
			['<IMG ALIGN=TOP SRC=' + path +'/line.gif>', '<IMG ALIGN=TOP SRC=' + path +'/spacer.gif>']
		]);
}
