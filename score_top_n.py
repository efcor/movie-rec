import os
import sys
import numpy as np
import tensorflow as tf
import json

def read_json_to_list(file_path):
  with open(file_path, 'r') as f:
    data = json.load(f)

  return data

# there's a copy of all-movies.json in the root of this repo
my_movie_titles = read_json_to_list('/home/travis/all-movies.json')
userId = sys.argv[1]
userIdF = f"\"{userId}\""
#numResults = sys.argv[2]

# note: this dir is the model built and exported from https://github.com/efcor/tf-sandbox/blob/main/model-attempt-3.ipynb
# note: this dir is zipped up to movie_recommender_model.zip in the base dir of this repo
loaded = tf.saved_model.load('/home/travis/movie_recommender_model')

my_ratings = {}
#my_movie_titles = []

for movie_title in my_movie_titles:
  my_ratings[movie_title] = loaded({
      "user_id": np.array([userIdF]),
      "movie_title": np.array([movie_title])
  })

counter = 0
print("Ratings:")
for title, score in sorted(my_ratings.items(), key=lambda x: x[1], reverse=True):
  if counter < 40:
    print(f"{title}: {score}")
    counter += 1
  else:
    break
