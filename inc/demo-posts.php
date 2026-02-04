<?php
/**
 * Chrysoberyl Demo Posts Data
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

function chrysoberyl_get_demo_posts_data()
{
    $categories = array('technology', 'business', 'lifestyle', 'entertainment', 'health');
    $posts = array();

    $lorem_long = '<h2>Introduction</h2>
    <p>In today\'s rapidly evolving world, staying ahead of the curve is more important than ever. Whether we are discussing technological advancements, business strategies, or lifestyle changes, the pace of innovation is relentless. This article explores the key trends shaping our future and offers actionable insights for navigating this complex landscape.</p>
    
    <h2>The Current Landscape</h2>
    <p>We are witnessing a paradigm shift in how we interact with the world around us. From the way we work to the way we communicate, digital transformation is permeating every aspect of our lives. <strong>Artificial Intelligence</strong>, big data, and cloud computing are no longer just buzzwords; they are the fundamental building blocks of modern infrastructure.</p>
    <ul>
        <li><strong>Innovation:</strong> Constant improvement is the new norm.</li>
        <li><strong>Adaptability:</strong> Flexibility is a key survival skill.</li>
        <li><strong>Sustainability:</strong> Long-term thinking is replacing short-term gains.</li>
    </ul>

    <h2>Key Challenges and Opportunities</h2>
    <p>With great change comes great challenge. One of the most pressing issues is the <em>digital divide</em>. As technology accelerates, ensuring equitable access becomes paramount. However, these challenges also present immense opportunities for those willing to innovate. Entrepreneurs and visionaries are finding new ways to solve old problems, creating value in efficient and sustainable ways.</p>

    <blockquote>"The only way to predict the future is to create it." – Peter Drucker</blockquote>

    <h2>Strategies for Success</h2>
    <p>To thrive in this environment, one must adopt a mindset of continuous learning. Upskilling and reskilling are essential. Furthermore, collaboration is key. No single entity can solve the world\'s complex problems alone. Strategic partnerships and open innovation ecosystems are driving progress across industries.</p>
    
    <h3>1. Embrace Technology</h3>
    <p>Don\'t fear automation; embrace it. Use tools to handle repetitive tasks so you can focus on strategic thinking and creativity.</p>

    <h3>2. Prioritize Well-being</h3>
    <p>In the hustle culture, it is easy to burn out. Mental health and physical well-being must be prioritized to sustain high performance over the long run.</p>

    <h2>Conclusion</h2>
    <p>As we look to the horizon, the possibilities are limitless. By staying informed, remaining adaptable, and fostering a culture of innovation, we can build a future that is not only prosperous but also inclusive and sustainable. The journey is just beginning.</p>

    ';

    // Content Templates by Category with specific images and AI excerpts
    $templates = array(
        'technology' => array(
            1 => array(
                'title' => 'The Future of Quantum Computing',
                'tags' => array('Quantum', 'Computing', 'Physics', 'Future'),
                'image' => 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?w=1200&h=800&fit=crop',
                'excerpt' => 'Quantum computing promises to solve problems that are currently impossible for classical computers. Learn how qubits, superposition, and entanglement are revolutionizing fields from cryptography to drug discovery.'
            ),
            2 => array(
                'title' => 'AI: Friend or Foe?',
                'tags' => array('AI', 'Ethics', 'Robotics', 'Tech'),
                'image' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=1200&h=800&fit=crop',
                'excerpt' => 'Artificial Intelligence offers immense benefits but raises critical ethical questions. We examine the balance between innovation and regulation, exploring the potential risks and rewards of an AI-driven future.'
            ),
            3 => array(
                'title' => '5G and the Connectivity Revolution',
                'tags' => array('5G', 'Mobile', 'Internet', 'Speed'),
                'image' => 'https://images.unsplash.com/photo-1614064641938-3bcee529cfc4?w=1200&h=800&fit=crop',
                'excerpt' => '5G is not just about faster download speeds; it is the backbone of the IoT era. Discover how ultra-low latency and massive device connectivity will enable smart cities, autonomous vehicles, and remote surgery.'
            ),
            4 => array(
                'title' => 'Cybersecurity in 2026',
                'tags' => array('Security', 'Hacking', 'Cyber', 'Data'),
                'image' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=1200&h=800&fit=crop',
                'excerpt' => 'As cyber threats become more sophisticated, so must our defenses. From AI-powered attacks to zero-trust architectures, we break down the emerging trends that every business needs to know to stay secure.'
            ),
            5 => array(
                'title' => 'The Rise of Smart Cities',
                'tags' => array('Smart City', 'IoT', 'Urban', 'Tech'),
                'image' => 'https://images.unsplash.com/photo-1480714378408-67cf0d13bc1b?w=1200&h=800&fit=crop',
                'excerpt' => 'Urban populations are exploding, and technology is the key to sustainable growth. Explore how sensors, data analytics, and integrated infrastructure are making cities more efficient, livable, and eco-friendly.'
            ),
        ),
        'business' => array(
            1 => array(
                'title' => 'Remote Work: The New Normal',
                'tags' => array('Remote Work', 'Management', 'HR', 'Culture'),
                'image' => 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=1200&h=800&fit=crop',
                'excerpt' => 'The traditional 9-to-5 office model is fading. We discuss the challenges and benefits of remote and hybrid work models, offering strategies for maintaining productivity, culture, and work-life balance.'
            ),
            2 => array(
                'title' => 'Startup Survival Guide',
                'tags' => array('Startup', 'Entrepreneur', 'Funding', 'Business'),
                'image' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=1200&h=800&fit=crop',
                'excerpt' => 'Launching a startup is easy; keeping it alive is hard. This guide covers essential tips for cash flow management, product-market fit, and scaling your business in a competitive market.'
            ),
            3 => array(
                'title' => 'Sustainable Business Practices',
                'tags' => array('Green', 'Eco', 'Sustainability', 'CSR'),
                'image' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb7d5afa?w=1200&h=800&fit=crop',
                'excerpt' => 'Profit and planet are no longer mutually exclusive. Learn how companies are integrating ESG (Environmental, Social, and Governance) principles to drive long-term value and attract conscious consumers.'
            ),
            4 => array(
                'title' => 'Digital Marketing Trends',
                'tags' => array('Marketing', 'SEO', 'Digital', 'Ads'),
                'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1200&h=800&fit=crop',
                'excerpt' => 'The digital marketing landscape changes daily. From video dominance to voice search and hyper-personalization, we highlight the strategies you need to cut through the noise and reach your audience.'
            ),
            5 => array(
                'title' => 'Leadership in Crisis',
                'tags' => array('Leadership', 'Management', 'Crisis', 'Strategy'),
                'image' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?w=1200&h=800&fit=crop',
                'excerpt' => 'Great leaders are forged in difficult times. Discover the core traits of resilient leadership, including empathy, decisive communication, and the ability to pivot strategies under pressure.'
            ),
        ),
        'lifestyle' => array(
            1 => array(
                'title' => 'Minimalism: A Guide',
                'tags' => array('Minimalism', 'Life', 'Declutter', 'Simple'),
                'image' => 'https://images.unsplash.com/photo-1494438639946-1ebd1d20bf85?w=1200&h=800&fit=crop',
                'excerpt' => 'Less is often more. This guide to minimalism helps you declutter your physical and mental space, allowing you to focus on what truly brings value and joy to your life.'
            ),
            2 => array(
                'title' => 'Sustainable Travel',
                'tags' => array('Travel', 'Eco', 'Green', 'Adventure'),
                'image' => 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=1200&h=800&fit=crop',
                'excerpt' => 'Explore the world without hurting it. We share tips for eco-friendly travel, from choosing carbon-neutral transport to supporting local communities and reducing your footprint while on the road.'
            ),
            3 => array(
                'title' => 'Urban Gardening',
                'tags' => array('Garden', 'Urban', 'Plants', 'Home'),
                'image' => 'https://images.unsplash.com/photo-1530836369250-ef72a3f5cda8?w=1200&h=800&fit=crop',
                'excerpt' => 'No backyard? No problem. Learn how to grow your own fresh herbs and vegetables in small apartments, balconies, and community plots, brining a touch of nature to city living.'
            ),
            4 => array(
                'title' => 'Digital Detox',
                'tags' => array('Wellness', 'Digital', 'Health', 'Life'),
                'image' => 'https://images.unsplash.com/photo-1515378960530-7c0da6231fb1?w=1200&h=800&fit=crop',
                'excerpt' => 'Constant connectivity can lead to burnout. We offer practical steps to disconnect from screens, reconnect with the real world, and reclaim your time and mental clarity.'
            ),
            5 => array(
                'title' => 'Slow Living 101',
                'tags' => array('Slow Living', 'Peace', 'Mindfulness', 'Life'),
                'image' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=1200&h=800&fit=crop',
                'excerpt' => 'In a fast-paced world, slowing down is a radical act. Discover the philosophy of slow living, which emphasizes quality over quantity, intentionality, and savoring the present moment.'
            ),
        ),
        'entertainment' => array(
            1 => array(
                'title' => 'The Golden Age of TV',
                'tags' => array('TV', 'Streaming', 'Shows', 'Drama'),
                'image' => 'https://images.unsplash.com/photo-1522869635100-9f4c5e86aa37?w=1200&h=800&fit=crop',
                'excerpt' => 'Television storytelling has reached new heights. We look at how streaming platforms are investing in cinema-quality production, attracting top talent, and changing the way we consume narratives.'
            ),
            2 => array(
                'title' => 'Indie Games Rising',
                'tags' => array('Gaming', 'Indie', 'Dev', 'Art'),
                'image' => 'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?w=1200&h=800&fit=crop',
                'excerpt' => 'Independent developers are pushing the boundaries of gaming. From artistic masterpieces to innovative mechanics, we explore how indie games are challenging major studios and capturing hearts.'
            ),
            3 => array(
                'title' => 'Music Streaming Wars',
                'tags' => array('Music', 'Spotify', 'Apple', 'Streaming'),
                'image' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=1200&h=800&fit=crop',
                'excerpt' => 'The battle for your ears is fiercer than ever. We compare the major music streaming platforms, analyzing their catalogs, audio quality, and payout models for artists.'
            ),
            4 => array(
                'title' => 'Cinema vs Streaming',
                'tags' => array('Movies', 'Cinema', 'Film', 'Industry'),
                'image' => 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=1200&h=800&fit=crop',
                'excerpt' => 'Is the theater experience dying? We debate the pros and cons of watching movies on the big screen versus the comfort of home, and what the future holds for the film industry.'
            ),
            5 => array(
                'title' => 'Podcast Revolution',
                'tags' => array('Podcast', 'Audio', 'Media', 'Talk'),
                'image' => 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?w=1200&h=800&fit=crop',
                'excerpt' => 'Podcasts have exploded into a major media form. From true crime to educational deep dives, we look at why audio storytelling is resonating with millions and how to find your next binge-listen.'
            ),
        ),
        'health' => array(
            1 => array(
                'title' => 'Sleep Hygiene 101',
                'tags' => array('Sleep', 'Health', 'Rest', 'Wellness'),
                'image' => 'https://images.unsplash.com/photo-1541781774459-bb2af2f05b55?w=1200&h=800&fit=crop',
                'excerpt' => 'Quality sleep is the foundation of good health. We delve into the science of sleep and practically explain how to optimize your bedroom environment and habits for a better night\'s rest.'
            ),
            2 => array(
                'title' => 'Plant-Based Diet Benefits',
                'tags' => array('Diet', 'Vegan', 'Food', 'Health'),
                'image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=1200&h=800&fit=crop',
                'excerpt' => 'More people are choosing plant-based diets for health and environmental reasons. We break down the nutritional benefits, potential pitfalls, and how to transition to a more plant-forward lifestyle.'
            ),
            3 => array(
                'title' => 'Mental Health at Work',
                'tags' => array('Mental Health', 'Work', 'Stress', 'Mind'),
                'image' => 'https://images.unsplash.com/photo-1493836512294-502baa1986e2?w=1200&h=800&fit=crop',
                'excerpt' => 'Workplace stress is a major health issue. This article discusses strategies for managing burnout, setting boundaries, and creating a supportive work culture that prioritizes mental well-being.'
            ),
            4 => array(
                'title' => 'HIIT Workouts Explained',
                'tags' => array('Fitness', 'Gym', 'HIIT', 'Sweat'),
                'image' => 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=1200&h=800&fit=crop',
                'excerpt' => 'High-Intensity Interval Training (HIIT) offers maximum results in minimum time. Learn the science behind the burn, how to structure a HIIT workout, and why it\'s so effective for cardiovascular health.'
            ),
            5 => array(
                'title' => 'Meditation for Beginners',
                'tags' => array('Meditation', 'Mindfulness', 'Peace', 'Yoga'),
                'image' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=1200&h=800&fit=crop',
                'excerpt' => 'Meditation is a simple practice with profound benefits. We strip away the mystique and provide a straightforward guide to starting a mindfulness practice that can reduce stress and improve focus.'
            ),
        ),
    );

    foreach ($categories as $cat) {
        // Generate 15 posts per category
        for ($i = 1; $i <= 15; $i++) {
            // Cycle through 5 templates
            $template_idx = (($i - 1) % 5) + 1;
            $template = $templates[$cat][$template_idx];

            // Generate title variants for duplicates
            $title = $template['title'];
            if ($i > 5) {
                $variant = ceil($i / 5);
                $title .= " (Part $variant)";
            }

            $slug = sanitize_title($title); // Deterministic slug for duplicate checking

            $posts[] = array(
                'title' => $title,
                'slug' => $slug,
                'category' => $cat,
                'tags' => $template['tags'],
                'excerpt' => $template['excerpt'],
                'image' => $template['image'],
                'content' => $lorem_long
            );
        }
    }

    return $posts;
}
