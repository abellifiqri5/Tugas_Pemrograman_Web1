// ========== CLASS TETRIS ==========
class Tetris {
    constructor() {
        this.COLS = 10;
        this.ROWS = 20;
        this.BLOCK_SIZE = 30;
        
        // Piece definitions
        this.PIECES = {
            I: { shape: [[1,1,1,1]], color: '#00f0f0' },
            O: { shape: [[1,1],[1,1]], color: '#f0f000' },
            T: { shape: [[0,1,0],[1,1,1]], color: '#a000f0' },
            S: { shape: [[0,1,1],[1,1,0]], color: '#00f000' },
            Z: { shape: [[1,1,0],[0,1,1]], color: '#f00000' },
            L: { shape: [[1,0,0],[1,1,1]], color: '#f0a000' },
            J: { shape: [[0,0,1],[1,1,1]], color: '#0000f0' }
        };
        
        this.PIECE_NAMES = ['I', 'O', 'T', 'S', 'Z', 'L', 'J'];
        
        this.board = [];
        this.score = 0;
        this.level = 1;
        this.lines = 0;
        this.gameOver = false;
        this.paused = false;
        this.gameRunning = false;
        
        this.currentPiece = null;
        this.nextPiece = null;
        this.dropInterval = null;
        this.animationId = null;
        
        // DOM elements
        this.canvas = document.getElementById('gameCanvas');
        this.ctx = this.canvas.getContext('2d');
        this.nextCanvas = document.getElementById('nextCanvas');
        this.nextCtx = this.nextCanvas.getContext('2d');
        
        this.scoreEl = document.getElementById('score');
        this.levelEl = document.getElementById('level');
        this.linesEl = document.getElementById('lines');
        this.finalScoreEl = document.getElementById('finalScore');
        this.finalLevelEl = document.getElementById('finalLevel');
        this.finalLinesEl = document.getElementById('finalLines');
        
        this.startBtn = document.getElementById('startBtn');
        this.pauseBtn = document.getElementById('pauseBtn');
        this.resetBtn = document.getElementById('resetBtn');
        this.playAgainBtn = document.getElementById('playAgainBtn');
        this.gameOverModal = document.getElementById('gameOverModal');
        
        this.init();
        this.setupControls();
    }
    
    init() {
        // Initialize board
        this.board = [];
        for (let row = 0; row < this.ROWS; row++) {
            this.board[row] = [];
            for (let col = 0; col < this.COLS; col++) {
                this.board[row][col] = 0;
            }
        }
        
        this.score = 0;
        this.level = 1;
        this.lines = 0;
        this.gameOver = false;
        this.paused = false;
        this.gameRunning = false;
        
        this.updateInfo();
        this.drawBoard();
        this.gameOverModal.classList.remove('active');
    }
    
    // ========== PIECE METHODS ==========
    createPiece(name) {
        const piece = this.PIECES[name];
        const shape = piece.shape.map(row => [...row]);
        return {
            name: name,
            shape: shape,
            color: piece.color,
            x: Math.floor((this.COLS - shape[0].length) / 2),
            y: 0
        };
    }
    
    getRandomPiece() {
        const name = this.PIECE_NAMES[Math.floor(Math.random() * this.PIECE_NAMES.length)];
        return this.createPiece(name);
    }
    
    rotatePiece(piece) {
        const shape = piece.shape;
        const rotated = [];
        for (let col = 0; col < shape[0].length; col++) {
            rotated[col] = [];
            for (let row = shape.length - 1; row >= 0; row--) {
                rotated[col].push(shape[row][col]);
            }
        }
        return rotated;
    }
    
    isValidPosition(shape, x, y) {
        for (let row = 0; row < shape.length; row++) {
            for (let col = 0; col < shape[row].length; col++) {
                if (shape[row][col]) {
                    const newX = x + col;
                    const newY = y + row;
                    if (newX < 0 || newX >= this.COLS || newY >= this.ROWS || newY < 0) {
                        return false;
                    }
                    if (newY >= 0 && this.board[newY][newX] !== 0) {
                        return false;
                    }
                }
            }
        }
        return true;
    }
    
    // ========== GAME METHODS ==========
    spawnPiece() {
        if (!this.nextPiece) {
            this.nextPiece = this.getRandomPiece();
        }
        
        this.currentPiece = this.createPiece(this.nextPiece.name);
        this.currentPiece.shape = this.nextPiece.shape.map(row => [...row]);
        this.currentPiece.color = this.nextPiece.color;
        
        this.nextPiece = this.getRandomPiece();
        
        if (!this.isValidPosition(this.currentPiece.shape, this.currentPiece.x, this.currentPiece.y)) {
            this.endGame();
            return false;
        }
        
        return true;
    }
    
    lockPiece() {
        const piece = this.currentPiece;
        for (let row = 0; row < piece.shape.length; row++) {
            for (let col = 0; col < piece.shape[row].length; col++) {
                if (piece.shape[row][col]) {
                    const boardRow = piece.y + row;
                    const boardCol = piece.x + col;
                    if (boardRow >= 0 && boardRow < this.ROWS) {
                        this.board[boardRow][boardCol] = piece.color;
                    }
                }
            }
        }
        
        this.clearLines();
        
        if (!this.spawnPiece()) {
            this.endGame();
        }
        
        this.drawBoard();
        this.drawNextPiece();
    }
    
    clearLines() {
        let cleared = 0;
        for (let row = this.ROWS - 1; row >= 0; row--) {
            if (this.board[row].every(cell => cell !== 0)) {
                this.board.splice(row, 1);
                this.board.unshift(new Array(this.COLS).fill(0));
                cleared++;
                row++; // Check same row again
            }
        }
        
        if (cleared > 0) {
            this.lines += cleared;
            this.score += this.calculateScore(cleared);
            this.level = Math.floor(this.lines / 5) + 1;
            this.updateInfo();
            this.updateDropSpeed();
        }
    }
    
    calculateScore(cleared) {
        const points = [0, 100, 300, 500, 800];
        return points[cleared] || 0;
    }
    
    updateDropSpeed() {
        if (this.dropInterval) {
            clearInterval(this.dropInterval);
        }
        const speed = Math.max(100, 1000 - (this.level - 1) * 80);
        this.dropInterval = setInterval(() => {
            if (!this.paused && this.gameRunning && !this.gameOver) {
                this.moveDown();
            }
        }, speed);
    }
    
    // ========== MOVEMENT ==========
    moveLeft() {
        if (!this.gameRunning || this.paused || this.gameOver) return;
        if (this.isValidPosition(this.currentPiece.shape, this.currentPiece.x - 1, this.currentPiece.y)) {
            this.currentPiece.x--;
            this.drawBoard();
        }
    }
    
    moveRight() {
        if (!this.gameRunning || this.paused || this.gameOver) return;
        if (this.isValidPosition(this.currentPiece.shape, this.currentPiece.x + 1, this.currentPiece.y)) {
            this.currentPiece.x++;
            this.drawBoard();
        }
    }
    
    moveDown() {
        if (!this.gameRunning || this.paused || this.gameOver) return;
        if (this.isValidPosition(this.currentPiece.shape, this.currentPiece.x, this.currentPiece.y + 1)) {
            this.currentPiece.y++;
            this.drawBoard();
        } else {
            this.lockPiece();
        }
    }
    
    rotatePieceWrapper() {
        if (!this.gameRunning || this.paused || this.gameOver) return;
        const rotated = this.rotatePiece(this.currentPiece);
        if (this.isValidPosition(rotated, this.currentPiece.x, this.currentPiece.y)) {
            this.currentPiece.shape = rotated;
            this.drawBoard();
        }
    }
    
    hardDrop() {
        if (!this.gameRunning || this.paused || this.gameOver) return;
        while (this.isValidPosition(this.currentPiece.shape, this.currentPiece.x, this.currentPiece.y + 1)) {
            this.currentPiece.y++;
        }
        this.lockPiece();
    }
    
    // ========== DRAWING ==========
    drawBoard() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        
        // Draw grid lines
        this.ctx.strokeStyle = 'rgba(255, 255, 255, 0.05)';
        this.ctx.lineWidth = 0.5;
        for (let row = 0; row <= this.ROWS; row++) {
            this.ctx.beginPath();
            this.ctx.moveTo(0, row * this.BLOCK_SIZE);
            this.ctx.lineTo(this.canvas.width, row * this.BLOCK_SIZE);
            this.ctx.stroke();
        }
        for (let col = 0; col <= this.COLS; col++) {
            this.ctx.beginPath();
            this.ctx.moveTo(col * this.BLOCK_SIZE, 0);
            this.ctx.lineTo(col * this.BLOCK_SIZE, this.canvas.height);
            this.ctx.stroke();
        }
        
        // Draw board
        for (let row = 0; row < this.ROWS; row++) {
            for (let col = 0; col < this.COLS; col++) {
                if (this.board[row][col] !== 0) {
                    this.drawBlock(col, row, this.board[row][col]);
                }
            }
        }
        
        // Draw current piece
        if (this.currentPiece) {
            const piece = this.currentPiece;
            for (let row = 0; row < piece.shape.length; row++) {
                for (let col = 0; col < piece.shape[row].length; col++) {
                    if (piece.shape[row][col]) {
                        const x = (piece.x + col) * this.BLOCK_SIZE;
                        const y = (piece.y + row) * this.BLOCK_SIZE;
                        this.drawBlockAt(x, y, piece.color);
                    }
                }
            }
        }
        
        // Draw game over overlay
        if (this.gameOver) {
            this.ctx.fillStyle = 'rgba(0, 0, 0, 0.7)';
            this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
            this.ctx.fillStyle = '#ff6b6b';
            this.ctx.font = 'bold 30px Arial';
            this.ctx.textAlign = 'center';
            this.ctx.textBaseline = 'middle';
            this.ctx.fillText('GAME OVER', this.canvas.width/2, this.canvas.height/2);
        } else if (this.paused && this.gameRunning) {
            this.ctx.fillStyle = 'rgba(0, 0, 0, 0.5)';
            this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
            this.ctx.fillStyle = '#fdcb6e';
            this.ctx.font = 'bold 40px Arial';
            this.ctx.textAlign = 'center';
            this.ctx.textBaseline = 'middle';
            this.ctx.fillText('⏸', this.canvas.width/2, this.canvas.height/2);
        }
    }
    
    drawBlock(col, row, color) {
        const x = col * this.BLOCK_SIZE;
        const y = row * this.BLOCK_SIZE;
        this.drawBlockAt(x, y, color);
    }
    
    drawBlockAt(x, y, color) {
        const size = this.BLOCK_SIZE - 1;
        const gradient = this.ctx.createLinearGradient(x, y, x + size, y + size);
        gradient.addColorStop(0, color);
        gradient.addColorStop(1, this.darkenColor(color, 0.3));
        
        this.ctx.fillStyle = gradient;
        this.ctx.fillRect(x + 0.5, y + 0.5, size, size);
        
        // Highlight
        this.ctx.fillStyle = 'rgba(255, 255, 255, 0.2)';
        this.ctx.fillRect(x + 0.5, y + 0.5, size * 0.3, 2);
        this.ctx.fillRect(x + 0.5, y + 0.5, 2, size * 0.3);
    }
    
    drawNextPiece() {
        this.nextCtx.clearRect(0, 0, this.nextCanvas.width, this.nextCanvas.height);
        if (this.nextPiece) {
            const shape = this.nextPiece.shape;
            const blockSize = 25;
            const offsetX = (this.nextCanvas.width - shape[0].length * blockSize) / 2;
            const offsetY = (this.nextCanvas.height - shape.length * blockSize) / 2;
            
            for (let row = 0; row < shape.length; row++) {
                for (let col = 0; col < shape[row].length; col++) {
                    if (shape[row][col]) {
                        const x = offsetX + col * blockSize;
                        const y = offsetY + row * blockSize;
                        this.nextCtx.fillStyle = this.nextPiece.color;
                        this.nextCtx.fillRect(x, y, blockSize - 1, blockSize - 1);
                        this.nextCtx.fillStyle = 'rgba(255, 255, 255, 0.2)';
                        this.nextCtx.fillRect(x, y, blockSize * 0.3, 2);
                        this.nextCtx.fillRect(x, y, 2, blockSize * 0.3);
                    }
                }
            }
        }
    }
    
    darkenColor(color, amount) {
        let r = parseInt(color.slice(1,2), 16) * 17;
        let g = parseInt(color.slice(2,3), 16) * 17;
        let b = parseInt(color.slice(3,4), 16) * 17;
        if (color.length === 7) {
            r = parseInt(color.slice(1,3), 16);
            g = parseInt(color.slice(3,5), 16);
            b = parseInt(color.slice(5,7), 16);
        }
        r = Math.floor(r * (1 - amount));
        g = Math.floor(g * (1 - amount));
        b = Math.floor(b * (1 - amount));
        return `rgb(${r}, ${g}, ${b})`;
    }
    
    updateInfo() {
        this.scoreEl.textContent = this.score;
        this.levelEl.textContent = this.level;
        this.linesEl.textContent = this.lines;
    }
    
    // ========== GAME CONTROL ==========
    startGame() {
        if (this.gameRunning) return;
        this.init();
        this.gameRunning = true;
        this.spawnPiece();
        this.updateDropSpeed();
        this.drawBoard();
        this.drawNextPiece();
        this.startBtn.disabled = true;
        this.pauseBtn.disabled = false;
        this.startBtn.textContent = '▶️ Bermain';
    }
    
    togglePause() {
        if (!this.gameRunning || this.gameOver) return;
        this.paused = !this.paused;
        this.pauseBtn.textContent = this.paused ? '▶️ Lanjut' : '⏸️ Pause';
        this.drawBoard();
    }
    
    endGame() {
        this.gameOver = true;
        this.gameRunning = false;
        if (this.dropInterval) {
            clearInterval(this.dropInterval);
            this.dropInterval = null;
        }
        this.finalScoreEl.textContent = this.score;
        this.finalLevelEl.textContent = this.level;
        this.finalLinesEl.textContent = this.lines;
        this.gameOverModal.classList.add('active');
        this.startBtn.disabled = false;
        this.startBtn.textContent = '🔄 Main Lagi';
        this.pauseBtn.disabled = true;
        this.drawBoard();
    }
    
    resetGame() {
        if (this.dropInterval) {
            clearInterval(this.dropInterval);
            this.dropInterval = null;
        }
        this.init();
        this.gameRunning = false;
        this.startBtn.disabled = false;
        this.startBtn.textContent = '▶️ Mulai';
        this.pauseBtn.disabled = true;
        this.pauseBtn.textContent = '⏸️ Pause';
        this.drawBoard();
        this.drawNextPiece();
        this.gameOverModal.classList.remove('active');
    }
    
    // ========== CONTROLS ==========
    setupControls() {
        // Keyboard
        document.addEventListener('keydown', (e) => {
            switch(e.key) {
                case 'ArrowLeft': e.preventDefault(); this.moveLeft(); break;
                case 'ArrowRight': e.preventDefault(); this.moveRight(); break;
                case 'ArrowDown': e.preventDefault(); this.moveDown(); break;
                case 'ArrowUp': e.preventDefault(); this.rotatePieceWrapper(); break;
                case ' ': e.preventDefault(); this.hardDrop(); break;
                case 'p':
                case 'P': this.togglePause(); break;
            }
        });
        
        // Buttons
        this.startBtn.addEventListener('click', () => this.startGame());
        this.pauseBtn.addEventListener('click', () => this.togglePause());
        this.resetBtn.addEventListener('click', () => this.resetGame());
        this.playAgainBtn.addEventListener('click', () => {
            this.gameOverModal.classList.remove('active');
            this.startGame();
        });
    }
}

// ========== START GAME ==========
document.addEventListener('DOMContentLoaded', () => {
    const game = new Tetris();
    window.game = game;
});