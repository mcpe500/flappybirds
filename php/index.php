<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flappy Bird</title>
    <style>
        body {
            margin: 0;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #70c5ce;
        }
        canvas {
            border: 1px solid #000;
            background: url('flappybirds/original-asset/flappy-bird-assets/sprites/background-day.png') repeat-x;
        }
    </style>
</head>
<body>
    <canvas id="gameCanvas"></canvas>
    <script>
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');

        canvas.width = 320;
        canvas.height = 480;

        const gravity = 0.5;
        let bird = {
            x: 50,
            y: 150,
            width: 34,
            height: 24,
            frameX: 0,
            frameY: 0,
            speed: 0,
            jump: -10,
            radius: 12,
            draw() {
                ctx.drawImage(birdSprite, this.frameX * this.width, this.frameY * this.height, this.width, this.height, this.x, this.y, this.width, this.height);
            },
            update() {
                this.speed += gravity;
                this.y += this.speed;

                if (this.y + this.height >= canvas.height) {
                    this.y = canvas.height - this.height;
                    this.speed = 0;
                }
            },
            flap() {
                this.speed = this.jump;
            }
        };

        const birdSprite = new Image();
        birdSprite.src = 'flappybirds/original-asset/flappy-bird-assets/sprites/bluebird-upflap.png';

        const pipes = [];
        const pipeWidth = 52;
        const pipeGap = 125;
        const pipeFrequency = 100;

        let score = 0;
        let gameFrame = 0;

        function drawPipes() {
            for (let i = 0; i < pipes.length; i++) {
                let topPipeY = pipes[i].y - pipeGap - pipeHeight;
                ctx.drawImage(pipeSprite, pipes[i].x, pipes[i].y, pipeWidth, pipeHeight);
                ctx.drawImage(pipeSprite, pipes[i].x, topPipeY, pipeWidth, pipeHeight);
                pipes[i].x -= 2;

                if (pipes[i].x + pipeWidth < 0) {
                    pipes.splice(i, 1);
                    i--;
                    score++;
                }

                if (bird.x + bird.width > pipes[i].x && bird.x < pipes[i].x + pipeWidth && bird.y < pipes[i].y + pipeHeight) {
                    // Collision with bottom pipe
                    resetGame();
                }
                if (bird.x + bird.width > pipes[i].x && bird.x < pipes[i].x + pipeWidth && bird.y + bird.height > pipes[i].y - pipeGap) {
                    // Collision with top pipe
                    resetGame();
                }
            }
        }

        function resetGame() {
            bird.y = 150;
            bird.speed = 0;
            pipes.length = 0;
            score = 0;
            gameFrame = 0;
        }

        const pipeSprite = new Image();
        pipeSprite.src = 'flappybirds/original-asset/flappy-bird-assets/sprites/pipe-green.png';
        const pipeHeight = 400;

        function handleScore() {
            ctx.fillStyle = '#000';
            ctx.font = '45px Arial';
            ctx.fillText(score, canvas.width / 2, 50);
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            bird.update();
            bird.draw();
            drawPipes();
            handleScore();
            gameFrame++;

            if (gameFrame % pipeFrequency === 0) {
                pipes.push({
                    x: canvas.width,
                    y: Math.random() * (canvas.height - pipeGap - pipeHeight) + pipeHeight / 2
                });
            }

            requestAnimationFrame(animate);
        }

        animate();

        window.addEventListener('keydown', (e) => {
            if (e.code === 'Space') {
                bird.flap();
            }
        });
    </script>
</body>
</html>
