import service from "@/Api/request";

class CommentRequest {

    static create(data){
        return service({
            url: '/comments/create',
            method:'post',
            data
        })
    }

}

export default CommentRequest;
