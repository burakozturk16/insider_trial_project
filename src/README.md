<h1 align="center">Burak  Öztürk</h1>

<br/>
[![Preview](https://img.youtube.com/vi/Lgy0atH3xXg /0.jpg)](https://www.youtube.com/watch?v=Lgy0atH3xXg )

## About Project

This is a trial day project that attempted to the Burak Öztürk by Insider. You can find the details about the project goals, problems, solutions, devTools, technology stack and re-search sources for Machine Learning.

Keys:
- The project designed thought to dependecy injection, so SimulationService can be change or FixtureService can be change.
- MyAlgorithm is very realistic to decide in game time, for example; Teams  weights can change at match minutes so players will be stressed and tired.

Stack:
- Laravel Framework to handling backend.
- PhpML library for predicate the results with high accuracy.
- Vuejs for designing frontend.
- Semantic UI for css framework.
- SQLite to store teams and results data.
- Composer to manage php packages.
- NPM to manage javascript packages.
- EPL Dataset downloaded from [here](https://sports-statistics.com).
- Planned to use for testing library is : Codeception (timeout)

## Goals & Problems of Project

This is a mid-level project which has two main goals/problems have to solve.

The first problem can solve a lot of methods, so I'll try to make round robin  algorithm to the best case of bigO for time complexity.

The main decision is how to guess scores of matches. Will I develop a basic algorithm or use machine learning algorithms to more reality of simulation?

I decided to create two version of calculation mechanism. Which one is using M.L algorithm to simulate games, second is creating a generic weight based algorithm. 

- The first one is **[round robin algorithm](https://en.wikipedia.org/wiki/Round-robin_scheduling)** to generate league fixture.
- Prediction of outcome match's score with training data.
- My Algorithm which is not using M.L
- Calculate the probability winning of teams. 


## About the generic algorithm 

The algorithm is easy to understand and does not use any machine learning algorithms, it is totally weight based.

## Researches about Machine Learning for Soccer Simulation
- Inspired Key Features formula in **Saint-Petersburg State University Albina Yezus**'s [Term Paper](https://www.math.spbu.ru/SD_AIS/documents/2014-12-341/2014-12-tw-15.pdf).
- Threshold Pruning on to local performances (DEF, MID, ATT) at section **2.4.6** in this academic [thesis](https://www.researchgate.net/profile/Gunjan-Kumar-6/publication/257048220_Machine_Learning_for_Soccer_Analytics/links/0c96052441dfabfc87000000/Machine-Learning-for-Soccer-Analytics.pdf).
