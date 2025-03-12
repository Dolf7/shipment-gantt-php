function calculateDuration(startTime, endTime) {
    const start = new Date(`1970-01-01T${startTime}Z`).getTime();
    const end = new Date(`1970-01-01T${endTime}Z`).getTime();
    return (end - start) / 60000; // returns duration in minutes
}

function calculateStartTime(duration, endTime) {
    const end = new Date(`1970-01-01T${endTime}Z`).getTime();
    const start = new Date(end - (duration * 60000));
    return start.toISOString().substr(11, 8);
}

function calculateEndTime(startTime, duration) {
    const start = new Date(`1970-01-01T${startTime}Z`).getTime();
    const end = new Date(start + (duration * 60000));
    return end.toISOString().substr(11, 8);
}


