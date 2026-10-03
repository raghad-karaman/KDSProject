from fastapi import FastAPI
from analytics import get_summary, get_top_regions, get_needs_distribution, get_map_data
from analytics import get_critical_region_count,get_distribution_summary,get_need_trend_7_days
from fastapi.middleware.cors import CORSMiddleware

app = FastAPI(title="KDS Python API")
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # 🔥 test için
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

@app.get("/")
def root():
    return {"status": "KDS Python API çalışıyor"}

@app.get("/analytics/summary")
def analytics_summary():
    return get_summary()

@app.get("/analytics/regions/top")
def analytics_top_regions():
    return get_top_regions()

@app.get("/analytics/needs/distribution")
def analytics_needs_distribution():
    return get_needs_distribution()

@app.get("/analytics/map")
def analytics_map():
    return get_map_data()


@app.get("/analytics/regions/critical-count")
def analytics_critical_count():
    return get_critical_region_count()

@app.get("/analytics/distribution/summary")
def distribution_summary():
    return get_distribution_summary()

@app.get("/analytics/needs/trend/7days")
def need_trend():
    return get_need_trend_7_days()

from analytics import get_team_status_distribution

@app.get("/analytics/teams/status-distribution")
def team_status_distribution():
    return get_team_status_distribution()
from analytics import assign_team, AssignRequest

@app.post("/analytics/assign")
def analytics_assign(req: AssignRequest):
    return assign_team(req)
