for branch in $(git branch -r | grep -v '\->'); do 
   echo "Rama: $branch" 
   git log -1 --pretty=format:"%h - %an, %ar : %s" $branch 
   echo "" 
done
