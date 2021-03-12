#!/bin/bash
chcp 65001

sqlite3 movies_rating.db < db_init.sql
@ echo off
echo "1. Найти все комедии, выпущенные после 2000 года, которые понравились мужчинам (оценка не ниже 4.5). Для каждого фильма в этом списке вывести название, год выпуска и количество таких оценок."
echo "--------------------------------------------------"
sqlite3 movies_rating.db -box -echo "SELECT movies.title as 'Movie', movies.year as 'Year', count(*) as 'The number of ratings not less than 4.5' from movies inner join(select * from ratings where rating>=4.5) ratings on ratings.movie_id = movies.id inner join(select * from users where users.gender = 'male') users on ratings.user_id = users.id where movies.year > 2000 and instr(genres, 'Comedy')>0 group by title;"
echo " "

echo "2. Провести анализ занятий (профессий) пользователей - вывести количество пользователей для каждого рода занятий. Найти самую распространенную и самую редкую профессию посетитетей сайта."
echo "--------------------------------------------------"
sqlite3 movies_rating.db -box -echo "create view "T2" as select occupation as 'Occupation', count(occupation) as 'Number' from users group by occupation;" 
sqlite3 movies_rating.db -box -echo "select * from T2; select Occupation, Number from(select *, max(Number)over() as 'Popular', min(Number)over() as 'Unpopular' from T2) where Number=Popular or Number=Unpopular; drop view T2;"
echo " "

echo "3. Найти все пары пользователей, оценивших один и тот же фильм. Устранить дубликаты, проверить отсутствие пар с самим собой. Для каждой пары должны быть указаны имена пользователей и название фильма, который они ценили."
echo "--------------------------------------------------"
sqlite3 movies_rating.db -box -echo "Select DISTINCT title as 'Movie', u1.name as 'User 1', u2.name as 'User 2' FROM ratings a, ratings b INNER JOIN movies ON a.movie_id = movies.id INNER JOIN users u1 ON a.user_id = u1.id INNER JOIN users u2 ON b.user_id = u2.id WHERE a.movie_id = b.movie_id and a.user_id < b.user_id order by title limit 100;"
echo " "

echo "4. Найти 10 самых свежих оценок от разных пользователей, вывести названия фильмов, имена пользователей, оценку, дату отзыва в формате ГГГГ-ММ-ДД."
echo "--------------------------------------------------"
sqlite3 movies_rating.db -box -echo "create view T4 as select ratings.user_id as 'userID', ratings.movie_id as 'movieID', ratings.rating as 'Rating', max(ratings.timestamp) as 'Date' from ratings group by ratings.user_id order by timestamp desc;"
sqlite3 movies_rating.db -box -echo "select movies.title as 'Movie', users.name as 'Name', Rating, date(Date,'unixepoch') as 'Date' from movies, users, T4 where movies.id = movieID and users.id = userID limit 10;drop view T4;"
echo " "

echo "5. Вывести в одном списке все фильмы с максимальным средним рейтингом и все фильмы с минимальным средним рейтингом. Общий список отсортировать по году выпуска и названию фильма. В зависимости от рейтинга в колонке "Рекомендуем" для фильмов должно быть написано "Да" или "Нет"."
echo "--------------------------------------------------"
sqlite3 movies_rating.db -box -echo "create view T5 as select movies.title as 'Movie', movies.year as 'Year', Rating from movies inner join(select ratings.movie_id, avg(ratings.rating) as 'Rating' from ratings group by ratings.movie_id) ratings on ratings.movie_id = movies.id;"
sqlite3 movies_rating.db -box -echo "select Movie, Year, Rating, case when MaxRating = Rating then 'Yes' else 'No' end as Recommend from(select *, max(Rating)over() as 'MaxRating', min(Rating)over() as 'MinRating' from T5) where Rating = MaxRating or Rating = MinRating order by Year, Movie;"
echo " "

echo "6. Вычислить количество оценок и среднюю оценку, которую дали фильмам пользователи-женщины в период с 2010 по 2012 год."
echo "--------------------------------------------------"
sqlite3 movies_rating.db -box -echo "create view T6 as select ratings.rating as 'Rating', ratings.timestamp as 'Date' from ratings inner join(select users.id, users.gender from users where users.gender = 'female') users on ratings.user_id = users.id where date(ratings.timestamp,'unixepoch') between '2010' and '2012' order by ratings.timestamp;"
sqlite3 movies_rating.db -box -echo "select count(Rating) as 'Number of ratings given by women', avg(Rating) as 'Average rating' from T6; drop view T6;"
echo " "

echo "7. Составить список фильмов с указанием их средней оценки и места в рейтинге по средней оценке. Полученный список отсортировать по году выпуска и названиям фильмов. В списке оставить первые 20 записей."
echo "--------------------------------------------------"
sqlite3 movies_rating.db -box -echo "select *, dense_rank()over(order by Rating desc) as 'Place' from T5 order by Year, Movie limit 20;drop view T5;"
echo " "

echo "8. Определить самый распространенный жанр фильма и количество фильмов в этом жанре."
echo "--------------------------------------------------"
sqlite3 movies_rating.db "create view statistics_genres as select value, genre from ((select count(*) as value, '(no genres listed)' as genre from movies where instr(movies.genres, '(no genres listed)') > 0)) union select value, genre from (select count(*) as value, 'Action' as genre from movies where instr(movies.genres, 'Action') > 0) union select value, genre from (select count(*) as value, 'Adventure' as genre from movies where instr(movies.genres, 'Adventure') > 0) union select value, genre from (select count(*) as value, 'Animation' as genre from movies where instr(movies.genres, 'Animation') > 0) union select value, genre from (select count(*) as value, 'Children' as genre from movies where instr(movies.genres, 'Children') > 0) union select value, genre from (select count(*) as value, 'Comedy' as genre from movies where instr(movies.genres, 'Comedy') > 0) union select value, genre from (select count(*) as value, 'Crime' as genre from movies where instr(movies.genres, 'Crime') > 0) union select value, genre from (select count(*) as value, 'Documentary' as genre from movies where instr(movies.genres, 'Documentary') > 0) union select value, genre from (select count(*) as value, 'Drama' as genre from movies where instr(movies.genres, 'Drama') > 0) union select value, genre from (select count(*) as value, 'Fantasy' as genre from movies where instr(movies.genres, 'Fantasy') > 0) union select value, genre from (select count(*) as value, 'Film-Noir' as genre from movies where instr(movies.genres, 'Film-Noir') > 0) union select value, genre from (select count(*) as value, 'Horror' as genre from movies where instr(movies.genres, 'Horror') > 0) union select value, genre from (select count(*) as value, 'Musical' as genre from movies where instr(movies.genres, 'Musical') > 0) union select value, genre from (select count(*) as value, 'Mystery' as genre from movies where instr(movies.genres, 'Mystery') > 0) union select value, genre from (select count(*) as value, 'Romance' as genre from movies where instr(movies.genres, 'Romance') > 0) union select value, genre from (select count(*) as value, 'Sci-Fi' as genre from movies where instr(movies.genres, 'Sci-Fi') > 0) union select value, genre from (select count(*) as value, 'Thriller' as genre from movies where instr(movies.genres, 'Thriller') > 0) union select value, genre from (select count(*) as value, 'War' as genre from movies where instr(movies.genres, 'War') > 0) union select value, genre from (select count(*) as value, 'Western' as genre from movies where instr(movies.genres, 'Western') > 0) union select value, genre from (select count(*) as value, 'IMAX' as genre from movies where instr(movies.genres, 'IMAX') > 0);"
sqlite3 movies_rating.db -box -echo "select max(value) as count, genre as genre_title from statistics_genres;"
sqlite3 movies_rating.db "drop view statistics_genres;"
pause