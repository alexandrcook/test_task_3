export const createNewBlog = async (name, headers) => {
    const res = await fetch(
        '/api/blogs/',
        {
            method: "POST",
            headers,
            body: JSON.stringify({
                name
            })
        }
    )
    return res.json();
}

export const createNewPost = async (subject, body, blog_id, headers) => {
    const res = await fetch(
        `/api/blogs/${blog_id}/posts/`,
        {
            method: "POST",
            headers,
            body: JSON.stringify({
                subject, body
            })
        }
    )
    return res.json();
}

export const createNewComment = async (post_id, message, headers) => {
    const res = await fetch(
        `/api/posts/${post_id}/comments`,
        {
            method: "POST",
            headers,
            body: JSON.stringify({
                post_id, message
            })
        }
    )
    return res.json();
}

export const getDataErrors = (data) =>
    Object.entries(data.errors).map(error => {
        const [key, value] = error;
        return {[key]: value[0]};
    });
