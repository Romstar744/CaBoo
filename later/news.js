document.addEventListener('DOMContentLoaded', function() {
//  Загрузка новостей при загрузке страницы
const newsContainer = document.getElementById('news-container');
fetch('../media/news.txt')
      .then(response => response.json())
      .then(data => {
           const newsItems = data.map(news => {
               const detailsList = news.details ? news.details.map(detail => `<li>${detail}</li>`).join('') : '';
               return `
                  <div class="news-item">
                      <h3>${news.title}</h3>
                      <p>${news.text}</p>
                      <ul class="news-details">
                        ${detailsList}
                      </ul>
                       <p class="news-author"><i>Автор: ${news.author}, Дата: ${news.date}</i></p>
                 </div>
                 `;
          }).join('');
          newsContainer.innerHTML = newsItems;
      })
      .catch(error => {
          console.error('Ошибка загрузки новостей:', error);
          newsContainer.innerHTML = '<p>Не удалось загрузить новости.</p>';
      });
});