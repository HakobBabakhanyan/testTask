<script setup>
import {Head, Link} from '@inertiajs/inertia-vue3';
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import {onMounted, reactive} from "vue";
import PostRequest from "@/Api/PostRequest";
import {ref} from 'vue'
import CommentRequest from "@/Api/CommentRequest";

const posts = ref([])
const message = ref([])
const replay = ref([])

onMounted(() => {
    PostRequest.getPosts().then((response) => {
        posts.value = response.data.posts
    })
});


function sendComment(message, postId, parentCommentId) {
    CommentRequest.create({
        message, post_id: postId, parent_id: parentCommentId
    }).then((response) => {
        posts.value = response.data.posts
    })
}

</script>

<template>
    <Head title="Welcome"/>
    <BreezeAuthenticatedLayout>
        <div class="container mx-auto">
            <div v-for="post in posts" class="mt-4 p-6 overflow-hidden rounded-lg shadow bg-gray-50 text-gray-800">
                <article>
                    <h2 class="text-xl font-bold">{{ post.title }}</h2>
                    <p class="mt-4 text-gray-600">{{ post.description }}</p>
                    <div v-for="comment in post.comments" class="flex items-center mt-8 space-x-4">
                        <div class="flex flex-col items-start">
                            <h3 class="text-sm font-bold">{{ comment.user.name }} </h3>
                            <p class="pl-2">
                                {{ comment.message }}
                            </p>
                            <time datetime="2021-02-18" class="text-sm text-gray-600 pl-2">
                                {{ comment.created_at }}
                            </time>
                            <a href="javascript:void(0)"
                               v-on:click="!replay[comment.id] ? replay[comment.id] = {active : true} : delete replay[comment.id]"
                               class="underline">Reply</a>
                            <div v-for="children in comment.children">
                                <div class="flex flex-col items-start pl-4">
                                    <h3 class="text-sm font-bold">{{ children.user.name }} </h3>
                                    <p class="pl-2">
                                        {{ children.message }}
                                    </p>
                                    <time datetime="2021-02-18" class="text-sm text-gray-600 pl-2">
                                        {{ children.created_at }}
                                    </time>
                                </div>
                            </div>
                            <div v-if="replay[comment.id] && replay[comment.id].active" class="pl-4">
                                <div class="flex flex-wrap -mx-3 mb-6">
                                    <h2 class="px-4 pt-3 pb-2 text-gray-800 text-lg"></h2>
                                    <div class="w-full md:w-full px-3 mb-2 mt-2">
                                    <textarea
                                        :disabled="!$page.props.auth.user"
                                        v-model='replay[comment.id].message'
                                        class="bg-gray-100 rounded border border-gray-400 leading-normal resize-none w-full h-20 py-2 px-3 font-medium placeholder-gray-700 focus:outline-none focus:bg-white"
                                        name="body"
                                        :placeholder='!!$page.props.auth.user?`Type Your Comment`:`Login to Comment`'
                                        required>
                                    </textarea>
                                    </div>
                                    <div class="w-full md:w-full flex items-start md:w-full px-3">
                                        <div class="-mr-1">
                                            <input type='button'
                                                   v-on:click="sendComment(replay[comment.id].message, post.id, comment.id); replay[comment.id].message = ''"
                                                   class="bg-white text-gray-700 font-medium py-1 px-4 border border-gray-400 rounded-lg tracking-wide mr-1 hover:bg-gray-100"
                                                   value='Post Comment'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <!-- comment form -->
                        <div class="">
                            <div class="flex flex-wrap -mx-3 mb-6">
                                <h2 class="px-4 pt-3 pb-2 text-gray-800 text-lg"></h2>
                                <div class="w-full md:w-full px-3 mb-2 mt-2">
                                    <textarea
                                        :disabled="!$page.props.auth.user"
                                        v-model='message[post.id]'
                                        class="bg-gray-100 rounded border border-gray-400 leading-normal resize-none w-full h-20 py-2 px-3 font-medium placeholder-gray-700 focus:outline-none focus:bg-white"
                                        name="body"
                                        :placeholder='!!$page.props.auth.user?`Type Your Comment`:`Login to Comment`'
                                        required>
                                    </textarea>
                                </div>
                                <div class="w-full md:w-full flex items-start md:w-full px-3">
                                    <div class="-mr-1">
                                        <input type='button'
                                               v-on:click="sendComment(message[post.id], post.id); message[post.id] = ''"
                                               class="bg-white text-gray-700 font-medium py-1 px-4 border border-gray-400 rounded-lg tracking-wide mr-1 hover:bg-gray-100"
                                               value='Post Comment'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<style scoped>

</style>
