This script allows you to run different routine tasks

MANUAL
1. Clone this file to your vagrant root (the same directory where www folder *www/workspace/apps* )
2. Go to the directory that contains f3desha.php with console
3. Type:
  php f3desha.php git_checkout -branch=<branch_name>

BEHAVIOuR
Script will run through all apps in apps folder and change branches to the branch you selected
in -branch option. If branch is already local it will just switch to it. If branch is only remote
it will download it and switch. If you already on selected branch, nothing will happen
