_alpha draft_
#Instructions for developers

## Working with repositories

### A: Cloning from original repository

1. clone repository: ```git clone git@github.com:IlchCMS/Ilch-2.0.git```
2. You are not able to push any changes! If you would like to push any changes please see our forking guide below!

### B: Forking from original repository

1. Fork from original repository https://github.com/IlchCMS/Ilch-2.0
2. clone from forked repository: ```git clone {your ssh clone path}```
3. Create pull request when you finish your work

### C: Get updates

1. commit you changes like ```git commit . -m "commitmsg"```
2. create remote: ```git remote add ilchorig git@github.com:IlchCMS/Ilch-2.0.git```
3. Fetch from remote: ```git fetch ilchorig```
4. merge fetched data: ```git merge ilchorig/master```

### D: Solve merge conflicts
1. use git mergetool: ```git mergetool``` (see following link for reference: http://git-scm.com/docs/git-mergetool)