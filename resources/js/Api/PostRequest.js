import service from "@/Api/request";

class PostRequest {

    static getPosts() {
        return service({
            url: 'posts',
            method: 'get',
        })
    }

}

export default PostRequest;
