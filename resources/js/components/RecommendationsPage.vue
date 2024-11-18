<template>
    <div>
        <h3 class="text-xl mb-4">Recommendations</h3>

        <p class="mb-4">Here are some movies we think you might like:</p>

        <div v-for="(movie, index) in moviesToRate" :key="index" class="border border-gray-300 shadow-md p-3 mb-3">
            <p class="mb-2">{{ movie }}</p>

            <button class="bg-sky-700 hover:bg-sky-900 text-neutral-100 py-1 px-2 mr-2 rounded-sm" @click="markLiked(movie)">
                Liked it
            </button>

            <button class="bg-neutral-500 hover:bg-neutral-700 text-neutral-100 py-1 px-2 rounded-sm" @click="markSkipped(movie)">
                Dismiss
            </button>
        </div>

        <div class="border border-gray-300 shadow-md p-3 mb-3">
            <p class="mb-2">Inception (2014)</p>

            <p>
                <a href="https://www.imdb.com/find/?q=inception" target="_blank" class="text-blue-500">View on imdb</a>
            </p>
        </div>

        <div v-show="!hasEnoughLikes" class="bg-blue-100 border-l-4 border-blue-500 text-blue-900 px-4 py-2 mt-6 mb-4" role="alert">
            <p>{{ remainingToLike }} more likes required before recommendations</p>
        </div>

        <div class="mt-6 mb-2">
            <button class="bg-sky-700 hover:bg-sky-900 text-neutral-100 py-1 px-2 rounded-sm" @click="rateMore">
                Rate more to refine
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['recommended'],
        methods: {
            rateMore() {
                this.$emit('change-page', 'home');
            }
        },
    }
</script>
