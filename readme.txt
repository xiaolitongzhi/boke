

初始化：git init
绑定用户：git config  --global user.name 'xiaolitongzhi'
绑定邮箱：git config  --global user.email 'xiaolitongzhi1996@126.com'
关联远程仓库： git remote add origin https://github.com/xiaolitongzhi/boke.git
关联推送：git push -u origin master:master
推送网络：git commit -m 'master 1测试'
更新composer:composer install


查看状态：git status
添加所有文件到仓库：git add -all
添加修改文件到仓库：git add .
提交文件 git commit  
删除文件 rm readme.txt     直接删除

版本控制：git reset
		跳到当前版本 git reset --hard HEAD  
		跳到上一版本 git reset --hard HEAD^  
		跳到上上版本 git reset --hard HEAD^^
		跳到指定版本 git reset --hard commit_id
		
		查看提交历史 git log
		git log --pretty=oneline     只输出一行
		
		查询所有提交历史  git reflog 
		
撤销放弃仓库代码		git checkout -- file_name


以上是配置。

使用：git clone 地址
创建分支：git checkout -b user1
推送至网络分支：git push origin user1:user1


分支管理
	
	查看分支：git branch
	创建分支：git branch <name>
	切换分支：git checkout <name>
	创建+切换分支：git checkout -b <name>
	合并某分支到当前分支：git merge <name>
	
	
	
	
组长：
初始化：git init
绑定用户：git config  --global user.name 'xiaolitongzhi'
绑定邮箱：git config  --global user.email 'xiaolitongzhi1996@126.com'
关联远程仓库： git remote add origin https://github.com/xiaolitongzhi/boke.git
关联推送：git push -u origin master:master  第一次提交用-u



组员：
克隆：git clone https://github.com/xiaolitongzhi/boke.git
配置用户名：git config  --global user.name 'xiaolitongzhi'
配置邮箱：git config  --global user.email 'xiaolitongzhi1996@126.com'
添加切换分支：git checkout -b home
推送代码：git push origin home:home
拉取代码：git pull origin master


合并分支：
查看分支：git fetch
确认合并：git merge origin/home 【空白文件指明合并之理由】




