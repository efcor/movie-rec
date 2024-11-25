<template>
    <div>
        <!-- ratings page -->
        <div v-show="page === 'rate'" style="width: 280px;">
            <div v-show="!isLoadingRecs">
                <h3 class="text-xl mb-4">Rate Movies</h3>

                <p class="mb-4">The more movies you rate, the more personalized the recommendations should be.</p>

                <div class="border border-gray-300 shadow-md p-3 mb-3" v-touch:swipe="onSwipeItem(1)">
                    <p class="mb-2">{{ moviesToRate[0] }}</p>

                    <div class="flex justify-between mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>

                        <span>Swipe</span>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </div>

                    <div class="flex justify-between">
                        <span>Dismiss</span>
                        <span>Liked it</span>
                    </div>
                </div>

                <div class="border border-gray-300 shadow-md p-3 mb-3" v-touch:swipe="onSwipeItem(2)">
                    <p class="mb-2">{{ moviesToRate[1] }}</p>

                    <div class="flex justify-between mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>

                        <span>Swipe</span>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </div>

                    <div class="flex justify-between">
                        <span>Dismiss</span>
                        <span>Liked it</span>
                    </div>
                </div>

                <div class="border border-gray-300 shadow-md p-3 mb-3" v-touch:swipe="onSwipeItem(3)">
                    <p class="mb-2">{{ moviesToRate[2] }}</p>

                    <div class="flex justify-between mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>

                        <span>Swipe</span>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </div>

                    <div class="flex justify-between">
                        <span>Dismiss</span>
                        <span>Liked it</span>
                    </div>
                </div>

                <div v-show="!hasEnoughLikes" class="bg-blue-100 border-l-4 border-blue-500 text-blue-900 px-4 py-2 mt-6 mb-4" role="alert">
                    <p>{{ remainingToLike }} more likes required before recommendations</p>
                </div>

                <div class="mt-6 mb-2">
                    <button class="bg-sky-700 hover:bg-sky-900 text-neutral-100 py-1 px-2 rounded-sm disabled:opacity-60 disabled:cursor-not-allowed" :disabled="recBtnDisabled" @click="getRecommendations">
                        {{ recBtnText }}
                    </button>
                </div>
            </div>

            <!-- loading the recommendations -->
            <div v-show="isLoadingRecs" class="flex items-center">
                <!-- https://github.com/n3r4zzurr0/svg-spinners/blob/main/svg-css/3-dots-bounce.svg?short_path=fa56388 -->
                <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><circle class="spinner_qM83" cx="4" cy="12" r="3"/><circle class="spinner_qM83 spinner_oXPr" cx="12" cy="12" r="3"/><circle class="spinner_qM83 spinner_ZTLf" cx="20" cy="12" r="3"/></svg>
                <span class="ml-6 text-gray-500">Hang tight-- we're getting your recommendations!</span>
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
                page: 'rate',
                isLoadingRecs: false,
            }
        },
        computed: {
            remainingToLike() {
                return this.likeThreshold - this.numLiked;
            },
            hasEnoughLikes() {
                return this.remainingToLike <= 0;
            },
            recBtnDisabled() {
                return !this.hasEnoughLikes || this.isLoadingRecs;
            },
            recBtnText() {
                return this.isLoadingRecs ? 'Getting recommendations...' : 'Get recommendations';
            }
        },
        methods: {
            markLiked(pos) {
                let movie = this.moviesToRate[pos - 1]
                this.likedMovies.push(movie);
                this.moviesToRate = removeStringFromArray(this.moviesToRate, movie);
                this.moviesToRate.push(this.movies.pop());
                this.numLiked++;
            },
            markSkipped(pos) {
                let movie = this.moviesToRate[pos - 1]
                this.skippedMovies.push(movie);
                this.moviesToRate = removeStringFromArray(this.moviesToRate, movie);
                this.moviesToRate.push(this.movies.pop());
            },
            getRecommendations() {
                this.isLoadingRecs = true;

                axios.post('/get-recommendations', {
                    movies: this.likedMovies,
                })
                .then(response => {
                    this.isLoadingRecs = false;
                    this.recommendations = response.data.movies;
                    this.page = 'recommendations';
                })
                .catch(error => {
                    this.isLoadingRecs = false;
                    alert('There was a problem getting the recommendations.');
                });
            },
            rateMore() {
                this.page = 'rate';
            },
            encodeTitle(title) {
                return window.encodeURIComponent(title);
            },
            onSwipeItem(pos) {
                return direction => {
                    console.log("Swiped pos: ", pos, " in direction ", direction);
                    if (direction === 'left') {
                        this.markSkipped(pos);
                    } else {
                        this.markLiked(pos);
                    }
                };
            },
        },
        mounted() {
            this.moviesToRate.push(this.movies.pop());
            this.moviesToRate.push(this.movies.pop());
            this.moviesToRate.push(this.movies.pop());
        }
    }
</script>
