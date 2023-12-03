function playAudio(number) {
    var audio = document.getElementById('audio_' + number);
    audio.play();
    return new Promise(resolve => {
        audio.addEventListener('ended', resolve);
    });
}


async function playSequence(sequence) {
    for (const number of sequence) {
        await playAudio(number);
    }
}
