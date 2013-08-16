# Git Workflow

> [_Git is a lot of things. Git is difficult..._](http://www.imdb.com/title/tt0393109/quotes?item=qt1030316)

[Git is easy](http://rogerdudler.github.io/git-guide/). [Git is hard](http://vimeo.com/60788996). Whatever your opinion of git, it helps to understand what is happening at a lower level. The book [Pro Git](http://git-scm.com/book) is incredibly useful and will have answers to most questions that come up. Of particular interest to good git workflow are the sections on [rebasing branches](http://git-scm.com/book/en/Git-Branching-Rebasing), [revision selection](http://git-scm.com/book/en/Git-Tools-Revision-Selection), and [rewriting history](http://git-scm.com/book/en/Git-Tools-Rewriting-History). If you are feeling particularly adventurous, check out [chapter 9](http://git-scm.com/book/en/Git-Internals).

In addition, the git man pages can be incredibly useful if you look in the right place (Ex: [interactive rebase](https://www.kernel.org/pub/software/scm/git/docs/git-rebase.html#_interactive_mode)). Finally, the [GitHub documentation](https://help.github.com/) is also not bad and, unlike most git documentation, looks like it was written for humans.

Linus Torvalds has [some thoughts](http://www.mail-archive.com/dri-devel@lists.sourceforge.net/msg39091.html) on maintaining a clean git history that are interesting but should not be taken as gospel because many ideas are not applicable to our project. More relevant are his thoughts a [few messages later](http://www.mail-archive.com/dri-devel@lists.sourceforge.net/msg39094.html) when he says:

> Git does allow people to do many different things, and solve problems different ways. I just want all the regular workflows to be "good practice", but then if you have to occasionally break the rules to solve some odd problem, go ahead and break the rules (and tell people why you  did it that way this time).

This should be your guiding git principle.

1.  Commits
2.  Pull Requests
3.  Rebasing
4.  Rebasing Master
5.  Merging
6.  Deleting Branches

## Commits

_(Copied directly from the Atlantic Coding Guidelines)_

Commit messages that reference tickets should end with "refs #ticket_number" or "fixes #ticket_number" if you would like to automatically resolve the ticket when pushing out the update. Commits that are connected to the same bug or feature request should start with a short one to three word label to make it easy to identify related commits when scanning the GIT log.

Commit messages should take the following format:
`Short label: what this commit accomplished, refs #1234`

For example:
`IBM Sponsored Blog: updated logo for phase II of sponsorship, refs #43210`

Occasionally, big changes (such as from a code refactoring) may require more explanation than is possible in two or three sentences. For these commits, feel free to put the commit description on a separate line from the label or the refs.

Quoting some good advice (slightly modified) from [Linus Torvalds](https://github.com/torvalds/subsurface/blob/2d88353b5965853c01a9d394f96a5d0545d86b21/README#L161-L181) on git commit messages:

A good commit message looks like this:
> 
>      Header line: explaining the commit in one line [refs/fixes #ticketnum if applicable]
> 
>      Body of commit message is a few lines of text, explaining things
>      in more detail, possibly giving some background about the issue
>      being fixed, etc etc.
> 
>      The body of the commit message can be several paragraphs, and
>      please do proper word-wrap and keep columns shorter than about
>      74 characters or so. That way "git log" will show things
>      nicely even when it's indented.
> 

That header line really should be meaningful, and really should be just one line.  That header line is what is shown in github and shortlog, and should summarize the change in one readable line of text, independently of the longer explanation.

## Pull Requests

The decision to open pull requests lies solely at the discretion of the developer (except for interns). If you have a small change, it is probably not worthy of a pull request. If the scope of the merge lies well within the developer's comfort zone, it may not be worth a pull request. Major architectural and feature overhauls are likely worthy of pull requesting if for nothing else than to have a second set of eyes on the code before it is deployed. Some developers use pull requests for sanity checks during development. They are a review mechanism and should be treated as such.

If you are new, we ask that your first few changes go through a pull request system to ensure that they are up to coding standards. It also helps us get to know your coding style.

## Rebasing

Rebase profusely (with caveats). Know the difference between non-interactive and [interactive](http://git-scm.com/docs/git-rebase#_interactive_mode) rebases and when each is appropriate. In particular, the following cases are alright for rebasing (in descending order of "alrightness"—1 being the most alright):

1.  Commits that haven't been pushed.
2.  Branches that have been pushed that you are the sole contributor on.
3.  Branches that are about to be merged.

The following cases should be avoided whenever possible:

1.  Rebasing a branch with collaborators.
2.  Rebasing a branch with its own branches.
3.  Rebasing master (see below)

### Rebasing Master

If you've pushed out your changes on master, unless there are very extraneous circumstances, you should _not_ rewrite that history. It's easy on a small team to communicate mistaken commits but one should not be in the habit of merging sloppy changes. If it is a small change, a simple revert will normally do the trick, and preserve the history for generations to come. Larger changes can be dealt with on a case-by-case basis.

Frankie considers an arbitrary and short amount of time (a Frankie minute, if you will) to be safe to rewrite the history on master. Be careful, it's shorter than you think.

## Merging

Strive for [fast forward merges](https://www.kernel.org/pub/software/scm/git/docs/git-merge.html#_fast_forward_merge). This is not possible in all cases but there are a few techniques that you can use to ensure that your changes can ff-merge (Note, you can attempt fast forward merge by running `git merge <branch> --ff-only`). This is a common workflow:

<pre>
# Starting on &lt;branch&gt;

git checkout master
git pull
git checkout &lt;branch&gt;
git rebase &lt;branch&gt;

# Depending on your comfort with rebasing, allow this to finish.

git push origin &lt;branch&gt; --force # This is only required if there is an open pull request.
git checkout master
git merge &lt;branch&gt;
git push origin master
</pre>

The `push --force` is only useful for github to know that a branch has been successfully merged into master (thus closing pull requests). Merging directly from the GitHub interface is easy but it normally leads to merge commits and should be used sparingly.

## ## Deleting Branches

We haven't historically deleted branches. The topic has come up lately in discussion and the decision to delete branches belongs solely to the developer who owns the branch. A safe deletion policy would be acceptable at some point in the future.

##### *-- Chris Barna, The Atlantic, 2013*