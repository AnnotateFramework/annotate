#!/usr/bin/env bash
GH=git@github.com:annotateframework

git subsplit init ${GH}/annotate.git

LAST_TAG=$(git tag -l | tail -n1);

git subsplit publish --heads="master" --tags=$LAST_TAG packages/Backend:${GH}/backend.git
git subsplit publish --heads="master" --tags=$LAST_TAG packages/CodeStyle:${GH}/code-style.git
git subsplit publish --heads="master" --tags=$LAST_TAG packages/Collections:${GH}/collections.git
git subsplit publish --heads="master" --tags=$LAST_TAG packages/Diagnostics:${GH}/diagnostics.git
git subsplit publish --heads="master" --tags=$LAST_TAG packages/Framework:${GH}/framework.git
git subsplit publish --heads="master" --tags=$LAST_TAG packages/Modules:${GH}/modules.git
git subsplit publish --heads="master" --tags=$LAST_TAG packages/Packages:${GH}/packages.git
git subsplit publish --heads="master" --tags=$LAST_TAG packages/Templating:${GH}/templating.git
git subsplit publish --heads="master" --tags=$LAST_TAG packages/Themes:${GH}/themes.git


rm -rf .subsplit/
