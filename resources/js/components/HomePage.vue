<template>
    <div>
        <!-- ratings page -->
        <div v-show="page === 'rate'">
            <h3 class="text-xl mb-4">Rate Movies</h3>

            <p class="mb-4">The more movies you rate, the more personalized the recommendations should be.</p>

            <div v-for="(movie, index) in moviesToRate" :key="index" class="border border-gray-300 shadow-md p-3 mb-3">
                <p class="mb-2">{{ movie }}</p>

                <button class="bg-sky-700 hover:bg-sky-900 text-neutral-100 py-1 px-2 mr-2 rounded-sm" @click="markLiked(movie)">
                    Liked it
                </button>

                <button class="bg-neutral-500 hover:bg-neutral-700 text-neutral-100 py-1 px-2 rounded-sm" @click="markSkipped(movie)">
                    Dismiss
                </button>
            </div>

            <div v-show="!hasEnoughLikes" class="bg-blue-100 border-l-4 border-blue-500 text-blue-900 px-4 py-2 mt-6 mb-4" role="alert">
                <p>{{ remainingToLike }} more likes required before recommendations</p>
            </div>

            <div class="mt-6 mb-2">
                <button class="bg-sky-700 hover:bg-sky-900 text-neutral-100 py-1 px-2 rounded-sm disabled:opacity-60 disabled:cursor-not-allowed" :disabled="!hasEnoughLikes" @click="getRecommendations">
                    Get recommendations
                </button>
            </div>
        </div>

        <!-- recommendations page -->
        <div v-show="page === 'recommendations'">
            <h3 class="text-xl mb-4">Recommendations</h3>

            <p class="mb-4">Here are some movies we think you might like:</p>

            <div v-for="(recommendation, index) in recommendations" :key="index" class="border border-gray-300 shadow-md p-3 mb-3">
                <p class="mb-2">{{ recommendation }}</p>

                <p>
                    <a :href="`https://www.imdb.com/find/?q=${encodeTitle(recommendation)}`" target="_blank" class="text-blue-500">View on imdb</a>
                </p>
            </div>

            <div class="mt-6 mb-2">
                <button class="bg-sky-700 hover:bg-sky-900 text-neutral-100 py-1 px-2 rounded-sm" @click="rateMore">
                    Rate more to refine
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    function shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
    }

    function removeStringFromArray(array, stringToRemove) {
        const index = array.indexOf(stringToRemove);
        if (index !== -1) {
            array.splice(index, 1);
        }
        return array;
    }

    export default {
        data() {
            return {
                likeThreshold: 20,
                movies: shuffleArray(window.movies),
                moviesToRate: [],
                likedMovies: [],
                skippedMovies: [],
                numLiked: 0,
                recommendations: [],
                page: 'rate'
            }
        },
        computed: {
            remainingToLike() {
                return this.likeThreshold - this.numLiked;
            },
            hasEnoughLikes() {
                return this.remainingToLike <= 0;
            }
        },
        methods: {
            markLiked(movie) {
                this.likedMovies.push(movie);
                this.moviesToRate = removeStringFromArray(this.moviesToRate, movie);
                this.moviesToRate.push(this.movies.pop());
                this.numLiked++;
            },
            markSkipped(movie) {
                this.skippedMovies.push(movie);
                this.moviesToRate = removeStringFromArray(this.moviesToRate, movie);
                this.moviesToRate.push(this.movies.pop());
            },
            getRecommendations() {
                axios.post('/get-recommendations', {
                    movies: this.likedMovies,
                })
                .then(response => {
                    this.recommendations = response.data.movies;
                    this.page = 'recommendations';
                })
                .catch(error => {
                    alert('There was a problem getting the recommendations.');
                });
            },
            rateMore() {
                this.page = 'rate';
            },
            encodeTitle(title) {
                return window.encodeURIComponent(title);
            }
        },
        mounted() {
            this.moviesToRate.push(this.movies.pop());
            this.moviesToRate.push(this.movies.pop());
            this.moviesToRate.push(this.movies.pop());
        }
    }
</script>
